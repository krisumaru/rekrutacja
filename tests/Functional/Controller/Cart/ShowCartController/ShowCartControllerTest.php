<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Cart\ShowCartController;

use App\Tests\Functional\WebTestCase;

class ShowCartControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new ShowCartControllerFixture());
    }

    public function test_shows_cart(): void
    {
        $this->client->request('GET', '/cart/fab8f7c3-a641-43c1-92d3-ee871a55fa8a');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();

        self::assertEquals([
            'total_price' => 10970,
            'products' => [
                [
                    'id' => 'fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7',
                    'name' => 'Product 1',
                    'price' => 1990,
                    'quantity' => 1
                ],
                [
                    'id' => '9670ea5b-d940-4593-a2ac-4589be784203',
                    'name' => 'Product 2',
                    'price' => 3990,
                    'quantity' => 1
                ],
                [
                    'id' => '15e4a636-ef98-445b-86df-46e1cc0e10b5',
                    'name' => 'Product 3',
                    'price' => 4990,
                    'quantity' => 1
                ],
            ]
        ], $response);
    }

    public function test_get_the_same_product_twice_to_cart(): void
    {
        $this->client->request('GET', '/cart/ca8a954a-ad03-4e48-bb9e-239dba3ae3df');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);

        self::assertEquals([
            'total_price' => 14000,
            'products' => [
                [
                    'id' => 'ae1ff511-4e57-470b-947b-4957154e9aa4',
                    'name' => 'Product 4',
                    'price' => 7000,
                    'quantity' => 2
                ],
            ]
        ], $response);
    }

    public function test_returns_404_if_cart_does_not_exist(): void
    {
        $this->client->request('GET', '/cart/2d6b5d93-e1fd-4f69-8293-832497be09cd');
        self::assertResponseStatusCodeSame(404);
    }
}
