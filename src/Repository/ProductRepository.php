<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Exception\ORMException;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (ORMException $e) {
            throw new \RuntimeException('Failed to save the product.');
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (ORMException $e) {
            throw new \RuntimeException('Failed to remove the product.');
        }
    }

    public function findByCategory(int $categoryId): QueryBuilder
    {
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.category = :categoryId')
                ->setParameter('categoryId', $categoryId)
                ->orderBy('p.id', 'ASC');
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to retrieve products by category.');
        }
    }

    public function searchByTitle(string $searchTerm): QueryBuilder
    {
        try {
            return $this->createQueryBuilder('p')
                ->andWhere('p.title LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->orderBy('p.id', 'ASC');
        } catch (\Exception $e) {

            throw new \RuntimeException('Failed to search products by title.');
        }
    }

    public function findAllWithPagination(): QueryBuilder
    {
        try {
            return $this->createQueryBuilder('p')
                ->orderBy('p.id', 'ASC');
        } catch (\Exception $e) {

            throw new \RuntimeException('Failed to retrieve products.');
        }
    }
}
