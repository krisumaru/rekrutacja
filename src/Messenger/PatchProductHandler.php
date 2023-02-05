<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PatchProductHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductService $service) { }

    public function __invoke(PatchProduct $command): void
    {
        $this->service->update($command->product, $command->name, $command->price);
    }
}
