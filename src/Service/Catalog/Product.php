<?php

declare(strict_types=1);

namespace App\Service\Catalog;

interface Product
{
    public function getId(): string;
    public function getName(): string;
    public function getPrice(): int;
    public function setName(string $name);
    public function setPrice(int $price);
}
