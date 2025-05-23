<?php

namespace App\Controller;

use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api/category')]
final class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    #[Route('/all', name: 'category_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getAll();
            return $this->json($categories);
        } catch (\Exception $e) {

            return $this->json(
                ['error' => 'Failed to fetch categories'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getById($id);

            if (!$category) {
                throw new NotFoundHttpException('Category not found');
            }

            return $this->json($category);
        } catch (NotFoundHttpException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {

            return $this->json(
                ['error' => 'Failed to fetch category'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/save', name: 'category_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON data');
            }

            $category = $this->categoryService->create($data);
            return $this->json($category, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {

            return $this->json(
                ['error' => 'Failed to create category'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/update/{id}', name: 'category_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON data');
            }

            $category = $this->categoryService->update($id, $data);

            if (!$category) {
                throw new NotFoundHttpException('Category not found');
            }

            return $this->json($category);
        } catch (NotFoundHttpException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (\InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {

            return $this->json(
                ['error' => 'Failed to update category'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/delete/{id}', name: 'category_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        try {
            $result = $this->categoryService->delete($id);

            if (!$result) {
                throw new NotFoundHttpException('Category not found');
            }

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {

            return $this->json(
                ['error' => 'Failed to delete category'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
