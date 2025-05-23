<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Repository\SizeRepository;
use App\Repository\ColorRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use InvalidArgumentException;
use DateTime;
use Symfony\Component\Serializer\SerializerInterface;

class CartItemService
{
    public function __construct(
        private CartItemRepository $cartItemRepository,
        private ProductRepository $productRepository,
        private SizeRepository $sizeRepository,
        private ColorRepository $colorRepository,
        private UserRepository $userRepository,
        private SerializerInterface $serializer
    ) {}

    /**
     * @throws EntityNotFoundException When related entities are not found
     * @throws InvalidArgumentException When quantity is invalid
     * @throws \RuntimeException When persisting fails
     */
    public function saveCartItem(
        int $userId,
        int $productId,
        int $quantity,
        int $sizeId,
        int $colorId
    ): string {
        try {
            if ($quantity <= 0) {
                throw new InvalidArgumentException('Quantity must be greater than zero');
            }

            $product = $this->productRepository->find($productId);
            if (!$product) {
                throw new EntityNotFoundException(sprintf('Product with ID %d not found', $productId));
            }

            $size = $this->sizeRepository->find($sizeId);
            if (!$size) {
                throw new EntityNotFoundException(sprintf('Size with ID %d not found', $sizeId));
            }

            $color = $this->colorRepository->find($colorId);
            if (!$color) {
                throw new EntityNotFoundException(sprintf('Color with ID %d not found', $colorId));
            }

            $user = $this->userRepository->find($userId);
            if (!$user) {
                throw new EntityNotFoundException(sprintf('User with ID %d not found', $userId));
            }

            if (!$size->isInStock()) {
                throw new InvalidArgumentException('This size is out of stock');
            }

            $existingCartItem = $this->cartItemRepository->findExistingCartItem(
                $userId,
                $productId,
                $sizeId,
                $colorId
            );

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->getQuantity() + $quantity;

                if ($newQuantity <= 0) {
                    throw new InvalidArgumentException('Resulting quantity would be invalid');
                }

                $subtotal = $newQuantity * $size->getPrice();

                $existingCartItem->setQuantity($newQuantity);
                $existingCartItem->setSubtotal($subtotal);
                $existingCartItem->setAddDate(new DateTime());
                $this->cartItemRepository->save($existingCartItem);

            } else {
                $cartItem = new CartItem();
                $cartItem->setUser($user);
                $cartItem->setProduct($product);
                $cartItem->setQuantity($quantity);
                $cartItem->setSize($size);
                $cartItem->setColor($color);
                $cartItem->setAddDate(new DateTime());

                $subtotal = $quantity * $size->getPrice();
                $cartItem->setSubtotal($subtotal);

                $this->cartItemRepository->save($cartItem);
            }

            $data = $existingCartItem ?? $cartItem;

            return $this->serializer->serialize($data, 'json', [
                'groups' => ['product:read', 'category:read', 'size:read', 'color:read', 'cart:read']
            ]);
        } catch (\Exception $e) {
            return throw new \RuntimeException('Failed to save product. Please try again later.'.$e);
        }
    }

    public function removeCartItem(int $cartItemId): array
    {
        try {
            $cartItem = $this->cartItemRepository->find($cartItemId);

            if (!$cartItem) {
                throw new EntityNotFoundException(sprintf('CartItem with ID %d not found', $cartItemId));
            }

            $this->cartItemRepository->remove($cartItem);

            return [
                'message' => 'Cart item removed successfully',
                'cart_item_id' => $cartItemId
            ];
        } catch (\Exception $e) {
            return [
                'message' => 'Failed to remove cart item: ' . $e->getMessage(),
            ];
        }
    }

    public function clearUserCart(int $userId): array
    {
        try {
            $user = $this->userRepository->find($userId);

            if (!$user) {
                throw new EntityNotFoundException(sprintf('User with ID %d not found', $userId));
            }

            $cartItems = $this->cartItemRepository->findByUser($userId);

            foreach ($cartItems as $cartItem) {
                $this->cartItemRepository->remove($cartItem);
            }

            return [
                'message' => 'User cart cleared successfully',
                'user_id' => $userId,
                'items_removed' => count($cartItems)
            ];
        } catch (\Exception $e) {
            return [
                'message' => 'Failed to clear user cart: ' . $e->getMessage(),
            ];
        }
    }


    public function saveMultipleCartItems(int $userId, array $items): string
    {
        $results = [];

        foreach ($items as $item) {
            $results[] = $this->saveCartItem(
                $userId,
                $item['product_id'],
                $item['quantity'],
                $item['size_id'],
                $item['color_id']
            );
        }

        return $this->serializer->serialize($results, 'json', [
            'groups' => ['product:read', 'category:read', 'size:read', 'color:read', 'cart:read']
        ]);;
    }


    public function getUserCartItems(int $userId): string
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new EntityNotFoundException(sprintf('User with ID %d not found', $userId));
        }

        $cartItems = $this->cartItemRepository->findByUser($userId);

        return $this->serializer->serialize($cartItems, 'json', [
            'groups' => ['product:read', 'category:read', 'size:read', 'color:read', 'cart:read']
        ]);;
    }
}
