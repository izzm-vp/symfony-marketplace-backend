<?php

namespace App\Controller;

use App\Service\ColorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/color')]
class ColorController extends AbstractController
{
    public function __construct(
        private ColorService $colorService
    ) {}

    #[Route('/save', name: 'color_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if (!isset($data['hex_code']) || !isset($data['name']) || !isset($data['product_id'])) {
            return $this->json(
                ['error' => 'Missing required fields'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $data = $this->colorService->createColor(
                $data['hex_code'],
                $data['name'],
                $data['product_id']
            );

            return new JsonResponse($data, 200, [], true);
        } catch (\Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/delete/{id}', name: 'color_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->colorService->deleteColor($id);
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
