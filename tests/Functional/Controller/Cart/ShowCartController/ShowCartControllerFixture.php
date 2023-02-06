<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Cart\ShowCartController;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ShowCartControllerFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $products = [
            new Product('fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', 'Product 1', 1990),
            new Product('9670ea5b-d940-4593-a2ac-4589be784203', 'Product 2', 3990),
            new Product('15e4a636-ef98-445b-86df-46e1cc0e10b5', 'Product 3', 4990),
        ];

        foreach ($products as $product) {
            $manager->persist($product);
        }

        $cart = new Cart('fab8f7c3-a641-43c1-92d3-ee871a55fa8a');

        foreach ($products as $product) {
            $cartProduct = new CartProduct($cart, $product);
            $cart->addCartProduct($cartProduct);
        }

        $manager->persist($cart);

        $cart2 = new Cart('ca8a954a-ad03-4e48-bb9e-239dba3ae3df');

        $product = new Product('ae1ff511-4e57-470b-947b-4957154e9aa4', 'Product 4', 7000);

        $cartProduct = new CartProduct($cart2, $product);
        $cartProduct->increaseQuantity();
        $cart2->addCartProduct($cartProduct);

        $manager->persist($product);

        $manager->persist($cart2);

        $manager->flush();
    }
}
