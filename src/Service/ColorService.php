<?php

namespace App\Service;

use App\Entity\Color;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Serializer\SerializerInterface;

class ColorService
{
    public function __construct(
        private ColorRepository $colorRepository,
        private ProductRepository $productRepository,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {}

    public function createColor(
        string $hexCode,
        string $name,
        int $productId
    ): string {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new BadRequestException('Product not found');
        }

        $color = new Color();
        $color->setHexCode($hexCode)
              ->setName($name)
              ->setProduct($product);

        $errors = $this->validator->validate($color);
        if (count($errors) > 0) {
            throw new BadRequestException((string) $errors);
        }

        $this->colorRepository->save($color, true);
        
        return $this->serializer->serialize($color, 'json', [
            'groups' => ['color:read']
        ]);;
    }

    public function deleteColor(int $id): void
    {
        $color = $this->colorRepository->find($id);
        if (!$color) {
            throw new BadRequestException('Color not found');
        }

        $this->colorRepository->remove($color, true);
    }
}