<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Catalog\UpdateController;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class UpdateControllerFixture extends AbstractFixture
{

    public const PRODUCT_ID = '0d46b18e-4620-4519-8640-e62ef81b92ec';
    public const PRODUCT_NAME = 'Product to update';
    public const PRODUCT_PRICE = 1990;

    public function load(ObjectManager $manager): void
    {
        $product = new Product(self::PRODUCT_ID, self::PRODUCT_NAME, self::PRODUCT_PRICE);
        $manager->persist($product);
        $manager->flush();
    }
}
