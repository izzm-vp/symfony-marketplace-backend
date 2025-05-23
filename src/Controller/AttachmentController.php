<?php

namespace App\Controller;

use App\Service\AttachmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/attachment')]
class AttachmentController extends AbstractController
{
    public function __construct(
        private AttachmentService $attachmentService
    ) {}

    #[Route('/save', name: 'attachment_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): JsonResponse
    {

        $file = $request->files->get('file');

        $productId = $request->request->get('product_id');

        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        if (!$productId) {
            return $this->json(['error' => 'product_id is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $attachment = $this->attachmentService->uploadAttachment($file, (int)$productId);

            return $this->json([
                'id' => $attachment->getId(),
                'path' => $attachment->getPath(),
                'is_image' => $attachment->isImage(),
                'product_id' => $attachment->getProduct()->getId(),
            ], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete/{id}', name: 'attachment_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->attachmentService->deleteAttachment($id);
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
