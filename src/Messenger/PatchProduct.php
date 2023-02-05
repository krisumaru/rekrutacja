<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Entity\Product;

class PatchProduct
{
    public function __construct(
        public readonly Product $product,
        public readonly ?string $name,
        public readonly ?int $price
    ) {}
}
