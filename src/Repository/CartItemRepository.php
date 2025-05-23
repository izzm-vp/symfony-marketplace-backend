<?php

namespace App\Repository;

use App\Entity\CartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 *
 * @method CartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartItem[]    findAll()
 * @method CartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function save(CartItem $entity): void
    {
        $this->getEntityManager()->persist($entity);

        $this->getEntityManager()->flush();
    }

    public function remove(CartItem $entity): void
    {
        $this->getEntityManager()->remove($entity);

        $this->getEntityManager()->flush();
    }

    public function findExistingCartItem(int $userId, int $productId, int $sizeId, int $colorId): ?CartItem
    {
        return $this->findOneBy([
            'user' => $userId,
            'product' => $productId,
            'size' => $sizeId,
            'color' => $colorId
        ]);
    }

    public function findByUser(int $userId): array
    {
        return $this->findBy(['user' => $userId], ['add_date' => 'DESC']);
    }
}
