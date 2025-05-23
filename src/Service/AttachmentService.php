<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Repository\AttachmentRepository;
use App\Repository\ProductRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class AttachmentService
{
    private string $uploadDir;
    private string $maxUploadSize;

    public function __construct(
        private AttachmentRepository $attachmentRepository,
        private ProductRepository $productRepository,
        private SluggerInterface $slugger,

        #[Autowire('%upload_dir%')]
        string $uploadDir,
        
        #[Autowire('%max_upload_size%')]
        string $maxUploadSize
    ) {
        $this->uploadDir = $uploadDir;
        $this->maxUploadSize = $maxUploadSize;

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }
    }

    public function uploadAttachment(UploadedFile $file, int $productId): Attachment
    {
        $this->validateFile($file);

        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new \InvalidArgumentException("Product with ID $productId not found");
        }

        $newFilename = $this->generateFilename($file);
        $filePath = 'uploads/' . $newFilename;

        try {
            $file->move($this->uploadDir, $newFilename);
        } catch (FileException $e) {
            throw new \RuntimeException('Failed to upload file: ' . $e->getMessage());
        }

        $attachment = new Attachment();
        $attachment->setPath($filePath)
            ->setIsImage($this->isImageFile($file))
            ->setProduct($product);

        $this->attachmentRepository->save($attachment, true);

        return $attachment;
    }

    public function deleteAttachment(int $id): void
    {
        $attachment = $this->attachmentRepository->findOneById($id);

        if (!$attachment) {
            throw new \InvalidArgumentException("Attachment with ID $id not found");
        }

        $this->deletePhysicalFile($attachment->getPath());
        $this->attachmentRepository->remove($attachment, true);
    }

    private function generateFilename(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        return $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    }

    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \RuntimeException($file->getErrorMessage());
        }

        if ($file->getSize() > $this->convertToBytes($this->maxUploadSize)) {
            throw new \RuntimeException("File exceeds maximum size of {$this->maxUploadSize}");
        }
    }

    private function deletePhysicalFile(string $filePath): void
    {
        $filename = basename($filePath);
        $fullPath = $this->uploadDir . '/' . $filename;

        if (file_exists($fullPath) && !is_dir($fullPath)) {
            if (!unlink($fullPath)) {
                throw new \RuntimeException("Failed to delete physical file");
            }
        }
    }

    private function isImageFile(UploadedFile $file): bool
    {
        return str_starts_with($file->getMimeType() ?? '', 'image/');
    }

    private function convertToBytes(string $size): int
    {
        $units = ['B' => 0, 'K' => 1, 'M' => 2, 'G' => 3];
        $unit = strtoupper(substr($size, -1));
        $number = (int) substr($size, 0, -1);

        return $number * (1024 ** ($units[$unit] ?? 0));
    }
}
