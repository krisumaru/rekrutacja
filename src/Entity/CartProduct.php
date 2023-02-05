<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_products')]
class CartProduct
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: 'Cart')]
    private Cart $cart;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: 'Product', fetch: 'EAGER')]
    private Product $product;

    #[ORM\Column]
    private int $quantity;

    public function __construct(Cart $cart, Product $product)
    {
        $this->cart = $cart;
        $this->product = $product;
        $this->quantity = 1;
    }

    public function getTotalPrice(): int
    {
        return $this->product->getPrice() * $this->quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function increaseQuantity(): void
    {
        ++$this->quantity;
    }
    public function decreaseQuantity(): void
    {
        --$this->quantity;
    }
}
