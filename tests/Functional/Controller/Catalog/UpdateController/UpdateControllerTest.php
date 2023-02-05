<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Catalog\UpdateController;

use App\Tests\Functional\WebTestCase;

class UpdateControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new UpdateControllerFixture());
    }

    /**
     * @dataProvider provider_update_product
     */
    public function test_update_product(?string $newName, ?int $newPrice, string $expectedName, int $expectedPrice): void
    {
        $data = [];
        if (null !== $newName) {
            $data['name'] = $newName;
        }
        if (null !== $newPrice) {
            $data['price'] = $newPrice;
        }
        $this->client->request('PATCH', '/products/'.UpdateControllerFixture::PRODUCT_ID, $data);

        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        $product = reset($response['products']);
        self::assertEquals($expectedName, $product['name']);
        self::assertEquals($expectedPrice, $product['price']);
    }

    public function test_404_when_unknown_id(): void
    {
        $this->client->request('PATCH', '/products/non-existing-id');

        self::assertResponseStatusCodeSame(404);
    }

    /**
     * @dataProvider provider_validation_error
     */
    public function test_validation_error($invalidData): void
    {
        $this->client->request('PATCH', '/products/'.UpdateControllerFixture::PRODUCT_ID, $invalidData);

        self::assertResponseStatusCodeSame(422);
    }

    public function provider_update_product(): array
    {
        $newName = 'UpdatedProduct';
        $newPrice = 5000;
        $defaultName = UpdateControllerFixture::PRODUCT_NAME;
        $defaultPrice = UpdateControllerFixture::PRODUCT_PRICE;
        return [
            [null, null, $defaultName, $defaultPrice],
            [$newName, null, $newName, $defaultPrice],
            [null, $newPrice, $defaultName, $newPrice],
            [$newName, $newPrice, $newName, $newPrice],
        ];
    }

    public function provider_validation_error(): array
    {
        return [
            [['name' => '']],
            [['price' => -1]],
            [['name' => '', 'price' => -1]],
        ];
    }
}
