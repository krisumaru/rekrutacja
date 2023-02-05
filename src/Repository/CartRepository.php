<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Cart as CartEntity;
use App\Entity\Product;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class CartRepository implements CartService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function addProduct(string $cartId, string $productId): void
    {
        $cart = $this->entityManager->find(CartEntity::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);

        if ($cart && $product) {
            $cart->addProduct($product);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }
    }

    public function removeProduct(string $cartId, string $productId): void
    {
        $cart = $this->entityManager->find(CartEntity::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);

        if ($cart && $product) {
            $cartProduct = $cart->getProductCartByProduct($product);
            if ($cartProduct) {
                $cartProduct->decreaseQuantity();
                if (0 === $cartProduct->getQuantity()) {
                    $this->entityManager->remove($cartProduct);
                }
                $this->entityManager->flush();
            }
        }
    }

    public function create(): CartEntity
    {
        $cart = new CartEntity(Uuid::uuid4()->toString());

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }
}
