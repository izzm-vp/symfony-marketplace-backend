<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @throws \RuntimeException On save failure
     */
    public function save(Category $category, bool $flush = true): void
    {
        try {
            $this->getEntityManager()->persist($category);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (ORMException $e) {
            throw new \RuntimeException('Failed to save category: ' . $e->getMessage());
        }
    }

    /**
     * @throws \RuntimeException On removal failure
     */
    public function remove(Category $category, bool $flush = true): void
    {
        try {
            $this->getEntityManager()->remove($category);
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (ORMException $e) {
            throw new \RuntimeException('Failed to remove category: ' . $e->getMessage());
        }
    }
}
