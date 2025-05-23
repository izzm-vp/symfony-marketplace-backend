<?php

namespace App\Controller;

use App\Service\SizeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/size')]
class SizeController extends AbstractController
{
    public function __construct(
        private SizeService $sizeService
    ) {}

    #[Route('/save', name: 'size_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['price']) || !isset($data['name']) || !isset($data['product_id'])) {
            return $this->json(
                ['error' => 'Missing required fields'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {

            $data = $this->sizeService->createSize(
                $data['price'],
                $data['name'],
                $data['product_id']
            );

            return new JsonResponse($data, JsonResponse::HTTP_CREATED, [], true);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (BadRequestException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred while creating the size'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/delete/{id}', name: 'size_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->sizeService->deleteSize($id);
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (BadRequestException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred while deleting the size'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
