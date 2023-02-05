<?php

declare(strict_types=1);

namespace App\Controller\Catalog;

use App\Entity\Product;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\Messenger\PatchProduct;
use App\ResponseBuilder\ErrorBuilder;
use App\Service\Catalog\ProductProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products/{id}", methods={"PATCH"}, name="product-patch")
 */
class UpdateController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(
        private readonly ErrorBuilder $errorBuilder,
        private readonly ProductProvider $productProvider,
    ) { }

    public function __invoke(Request $request, ?Product $product): Response
    {
        if (null === $product) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Invalid id.'),
                Response::HTTP_NOT_FOUND
            );
        }
        $name = $request->get('name');
        $price = $request->get('price');
        $price = null !== $price
            ? (int)$price
            : null;

        if ($name === '' || (null !== $price && ($price) < 1)) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Invalid name or price.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new PatchProduct($product, $name, $price));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}
