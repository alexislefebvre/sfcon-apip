<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @implements ProviderInterface<Book[]|Book|null>
 */
final readonly class BookProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable|Book|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->entityManager->getRepository(Book::class)->findAll();
        }

        return $this->entityManager->getRepository(Book::class)->find($uriVariables['id']);
    }
}
