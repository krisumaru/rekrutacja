<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Cart\Cart as CartInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements CartInterface
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: 'CartProduct', cascade: ['PERSIST'])]
    private Collection $cartProducts;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_reduce(
            $this->cartProducts->toArray(),
            static fn(int $total, CartProduct $cartProduct): int => $total + $cartProduct->getTotalPrice(),
            0
        );
    }

    #[Pure]
    public function isFull(): bool
    {
        $count = 0;
        foreach ($this->cartProducts as $cartProduct) {
            $count += $cartProduct->getQuantity();
        }
        return $count >= self::CAPACITY;
    }

    public function addProduct(Product $product): void
    {
        $cartProduct = $this->getProductCartByProduct($product);
        if ($cartProduct) {
            $cartProduct->increaseQuantity();
        } else {
            $this->cartProducts->add(new CartProduct($this, $product));
        }
    }

    public function getProductCartByProduct(Product $product): ?CartProduct
    {
        foreach ($this->cartProducts as $cartProduct) {
            if ($cartProduct->getProduct()->getId() === $product->getId())
            {
                return $cartProduct;
            }
        }
        return null;
    }

    public function addCartProduct(CartProduct $cartProduct)
    {
        $this->cartProducts->add($cartProduct);
    }

    public function getCartProducts(): iterable
    {
        return $this->cartProducts->getIterator();
    }
}
