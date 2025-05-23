<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private PaginatorInterface $paginator,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {}

    public function getPaginatedProducts(Request $request): string
    {
        try {
            $page = max(1, $request->query->getInt('page', 1));
            $limit = max(1, $request->query->getInt('limit', 10));

            $queryBuilder = $this->productRepository->findAllWithPagination();
            $pagination = $this->paginator->paginate($queryBuilder, $page, $limit);

            $data = [
                'products' => $pagination->getItems(),
                'total' => $pagination->getTotalItemCount(),
                'page' => $pagination->getCurrentPageNumber(),
                'pages' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
            ];

            return $this->serializer->serialize($data, 'json', [
                'groups' => ['product:read', 'category:read']
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to retrieve products. Please try again later.');
        }
    }

    public function getProductsByCategory(Request $request, int $categoryId): string
    {
        try {
            $page = max(1, $request->query->getInt('page', 1));
            $limit = max(1, $request->query->getInt('limit', 10));

            if ($categoryId <= 0) {
                throw new \InvalidArgumentException('Invalid category ID');
            }

            $queryBuilder = $this->productRepository->findByCategory($categoryId);
            $pagination = $this->paginator->paginate($queryBuilder, $page, $limit);

            $data = [
                'items' => $pagination->getItems(),
                'total' => $pagination->getTotalItemCount(),
                'page' => $pagination->getCurrentPageNumber(),
                'pages' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
            ];

            return $this->serializer->serialize($data, 'json', [
                'groups' => ['product:read', 'category:read']
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to retrieve products by category. Please try again later.');
        }
    }

    public function searchProducts(Request $request): string
    {
        try {
            $searchTerm = trim($request->query->get('q', ''));
            $page = max(1, $request->query->getInt('page', 1));
            $limit = max(1, $request->query->getInt('limit', 10));

            if (empty($searchTerm)) {
                throw new \InvalidArgumentException('Search term cannot be empty');
            }

            $queryBuilder = $this->productRepository->searchByTitle($searchTerm);
            $pagination = $this->paginator->paginate($queryBuilder, $page, $limit);

            $data = [
                'items' => $pagination->getItems(),
                'total' => $pagination->getTotalItemCount(),
                'page' => $pagination->getCurrentPageNumber(),
                'pages' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
            ];

            return $this->serializer->serialize($data, 'json', [
                'groups' => ['product:read', 'category:read']
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to search products. Please try again later.');
        }
    }

    public function getProduct(int $id): string
    {
        try {
            if ($id <= 0) {
                throw new \InvalidArgumentException('Invalid product ID');
            }

            $product = $this->productRepository->find($id);

            if (!$product) {
                throw new NotFoundHttpException('Product not found');
            }

            return $this->serializer->serialize($product, 'json', [
                'groups' => ['product:read', 'category:read']
            ]);
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to retrieve product. Please try again later.');
        }
    }

    public function createProduct(string $title, string $description, int $categoryId): string
    {
        try {
            $category = $this->categoryRepository->find($categoryId);
            if (!$category) {
                throw new \InvalidArgumentException('Category not found.');
            }

            $product = new Product();
            $product->setTitle(trim($title));
            $product->setDescription(trim($description));
            $product->setCategory($category);

            $errors = $this->validator->validate($product);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException((string) $errors);
            }

            $this->productRepository->save($product, true);

            return $this->serializer->serialize($product, 'json', [
                'groups' => ['product:read', 'category:read']
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to create product: ' . $e->getMessage());
        }
    }


    public function updateProduct(
        int $id,
        ?string $title,
        ?string $description,
        ?int $categoryId
    ): string {
        try {
            $product = $this->productRepository->find($id);

            if ($title !== null) {
                $product->setTitle(trim($title));
            }

            if ($description !== null) {
                $product->setDescription(trim($description));
            }

            if ($categoryId !== null) {
                $category = $this->categoryRepository->find($categoryId);
                if (!$category) {
                    throw new \InvalidArgumentException('Category not found.');
                }
                $product->setCategory($category);
            }

            $errors = $this->validator->validate($product);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException((string) $errors);
            }

            $this->productRepository->save($product, true);

            return $this->serializer->serialize($product, 'json', [
                'groups' => ['product:read', 'category:read']
            ]);
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to update product: ' . $e->getMessage());
        }
    }


    public function deleteProduct(int $id): void
    {
        try {
            $product = $this->productRepository->find($id);
            $this->productRepository->remove($product, true);
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to delete product. Please try again later.');
        }
    }
}
