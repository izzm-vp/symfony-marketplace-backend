<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService
    ) {}

    #[Route('/all', name: 'product_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->productService->getPaginatedProducts($request);

            return new JsonResponse($data, 200, [], true);;
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/search', name: 'product_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->productService->searchProducts($request);

            return new JsonResponse($data, 200, [], true);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/category/{categoryId}', name: 'product_by_category', methods: ['GET'])]
    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        try {
            $data = $this->productService->getProductsByCategory($request, $categoryId);

            return new JsonResponse($data, 200, [], true);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->productService->getProduct($id);

            return new JsonResponse($data, 200, [], true);
        } catch (NotFoundHttpException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/save', name: 'product_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        try {

            $data = json_decode($request->getContent(), true);

            $requiredFields = ['title', 'description', 'category_id'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new \InvalidArgumentException(sprintf('%s is required', $field));
                }
            }

            $data = $this->productService->createProduct(
                $data['title'],
                $data['description'],
                (int)$data['category_id']
            );

            return new JsonResponse($data, 200, [], true);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/update/{id}', name: 'product_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON data');
            }

            $data = $this->productService->updateProduct(
                $id,
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['category_id'] ?? null
            );

            return new JsonResponse($data, 200, [], true);
        } catch (NotFoundHttpException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/delete/{id}', name: 'product_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);

            return $this->json([
                'message' => 'Product deleted successfully'
            ]);
        } catch (NotFoundHttpException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
