<?php

namespace App\Controller;

use App\Service\CartItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/cart', name: 'api_cart_')]
class CartController extends AbstractController
{
    public function __construct(
        private CartItemService $cartItemService
    ) {}

    #[Route('/save', name: 'add_item', methods: ['POST'])]
    public function addItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $result = $this->cartItemService->saveCartItem(
                $data['user_id'],
                $data['product_id'],
                $data['quantity'],
                $data['size_id'],
                $data['color_id']
            );

            if (isset($result['message']) && strpos($result['message'], 'Failed') !== false) {
                return $this->json($result, Response::HTTP_BAD_REQUEST);
            }

            return new JsonResponse($result, 200, [], true);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'An error occurred while adding item to cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/items', name: 'add_multiple_items', methods: ['POST'])]
    public function addMultipleItems(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            if (!isset($data['user_id']) || !isset($data['items'])) {
                throw new \InvalidArgumentException('Missing required fields: user_id or items');
            }

            $results = $this->cartItemService->saveMultipleCartItems(
                $data['user_id'],
                $data['items']
            );

            return new JsonResponse($results, 200, [], true);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'An error occurred while adding multiple items to cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/item/{id}', name: 'remove_item', methods: ['DELETE'])]
    public function removeItem(int $id): JsonResponse
    {
        try {
            $result = $this->cartItemService->removeCartItem($id);

            if (isset($result['message']) && strpos($result['message'], 'Failed') !== false) {
                return $this->json($result, Response::HTTP_BAD_REQUEST);
            }

            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'An error occurred while removing item from cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/clear/{userId}', name: 'clear_cart', methods: ['DELETE'])]
    public function clearCart(int $userId): JsonResponse
    {
        try {
            $result = $this->cartItemService->clearUserCart($userId);

            if (isset($result['message']) && strpos($result['message'], 'Failed') !== false) {
                return $this->json($result, Response::HTTP_BAD_REQUEST);
            }

            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'An error occurred while clearing the cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/user/{userId}', name: 'get_user_cart', methods: ['GET'])]
    public function getUserCart(int $userId): JsonResponse
    {
        try {
            
            $cartItems = $this->cartItemService->getUserCartItems($userId);

            return new JsonResponse($cartItems, 200, [], true);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'An error occurred while fetching user cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
