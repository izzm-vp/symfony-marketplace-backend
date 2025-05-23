<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
    public function __construct(
        private CategoryRepository $repository,
        private EntityManagerInterface $em
    ) {}

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getById(int $id): Category
    {
        if (!$category = $this->repository->find($id)) {
            throw new NotFoundHttpException("Category not found");
        }
        return $category;
    }

    /**
     * @throws \RuntimeException
     */
    public function create(array $data): Category
    {
        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description'] ?? null);

        try {
            $this->repository->save($category);
            return $category;
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Failed to create category: ' . $e->getMessage());
        }
    }

    /**
     * @throws NotFoundHttpException|\RuntimeException
     */
    public function update(int $id, array $data): Category
    {
        $category = $this->getById($id);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }

        if (array_key_exists('description', $data)) {
            $category->setDescription($data['description']);
        }

        try {
            $this->repository->save($category);
            return $category;
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * @throws NotFoundHttpException|\RuntimeException
     */
    public function delete(int $id): void
    {
        $category = $this->getById($id);

        try {
            $this->repository->remove($category);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Failed to delete category: ' . $e->getMessage());
        }
    }
}
