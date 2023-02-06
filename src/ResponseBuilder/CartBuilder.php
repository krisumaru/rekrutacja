<?php

declare(strict_types=1);

namespace App\ResponseBuilder;

use App\Service\Cart\Cart;

class CartBuilder
{
    public function __invoke(Cart $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        foreach ($cart->getCartProducts() as $cartProduct) {
            $product = $cartProduct->getProduct();
            $data['products'][] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $cartProduct->getQuantity(),
            ];
        }

        return $data;
    }
}
