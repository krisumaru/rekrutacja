<?php

declare(strict_types=1);

namespace App\Service\Cart;

use App\Entity\CartProduct;

interface Cart
{
    public function getId(): string;
    public function getTotalPrice(): int;
    public function isFull(): bool;

    /**
     * @return CartProduct[]
     */
    public function getCartProducts(): iterable;
}
