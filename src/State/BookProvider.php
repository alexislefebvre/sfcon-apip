<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Book;

/**
 * @implements ProviderInterface<Book[]|Book|null>
 */
class BookProvider implements ProviderInterface
{
    // TODO: check if the exception “Typed property App\\State\\BookProvider::$data must not be accessed before initialization” still occur later
    private array $data = [];

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable|Book|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->data;
        }

        return $this->data[$uriVariables['id']] ?? null;
    }
}
