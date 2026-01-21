<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

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
        $apiQueryParameters = $context['request']->attributes->get('_api_query_parameters');
        $bookRepository = $this->entityManager->getRepository(Book::class);

        if ($operation instanceof CollectionOperationInterface) {
            if ($name = $apiQueryParameters['name'] ?? null) {
                return $this->findBookByName($name);
            } elseif ($isbn = $apiQueryParameters['isbn'] ?? null) {
                return $this->findBookByIsbn($isbn);
            }

            return $bookRepository->findAll();
        }

        return $bookRepository->find($uriVariables['id']);
    }

    private function findBookByName(string $name): iterable
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->like('book.title', ':name'),
            )
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()->getResult()
        ;
    }
    private function findBookByIsbn(string $isbn): iterable
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->eq('book.isbn', ':isbn'),
            )
            ->setParameter('isbn', $isbn)
            ->getQuery()->getResult()
        ;
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->getRepository(Book::class)
            ->createQueryBuilder('book');
    }
}
