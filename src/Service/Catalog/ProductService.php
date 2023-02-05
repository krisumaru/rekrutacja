<?php

declare(strict_types=1);

namespace App\Service\Catalog;

interface ProductService
{
    public function add(string $name, int $price): Product;

    public function remove(string $id): void;

    public function update(Product $product, ?string $name, ?int $price);
}
