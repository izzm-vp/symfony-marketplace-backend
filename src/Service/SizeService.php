<?php

namespace App\Service;

use App\Entity\Size;
use App\Repository\SizeRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Serializer\SerializerInterface;

class SizeService
{
    public function __construct(
        private SizeRepository $sizeRepository,
        private ProductRepository $productRepository,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {}

    public function createSize(
        string $price,
        string $name,
        int $productId
    ): string {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new BadRequestException('Product not found');
        }

        $size = new Size();
        $size->setName($name)
            ->setPrice($price)  
            ->setProduct($product)
            ->setInStock(true);

        $errors = $this->validator->validate($size);
        if (count($errors) > 0) {
            throw new BadRequestException((string) $errors);
        }

        $this->sizeRepository->save($size, true);

        return $this->serializer->serialize($size, 'json', [
            'groups' => ['size:read']
        ]);
    }

    public function deleteSize(int $id): void
    {
        $size = $this->sizeRepository->find($id);
        if (!$size) {
            throw new BadRequestException('Size not found');
        }

        $this->sizeRepository->remove($size, true);
    }
}
