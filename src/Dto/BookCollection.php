<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Entity\Book as BookEntity;
use App\State\BookProvider;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints\Isbn;

#[ApiResource(
    shortName: 'Book',
    provider: BookProvider::class,
    jsonStream: true,
    operations: [
        new GetCollection(
            uriTemplate: '/books',
            output: BookCollection::class,
            parameters: [
                'name' => new QueryParameter(
                    property: 'title',
                    filter: new PartialSearchFilter(),
                ),
                'isbn' => new QueryParameter(
                    filter: new ExactFilter(),
                    constraints: [new Isbn()],
                ),
            ],
        ),
    ],
)]
#[Map(source: BookEntity::class)]
final class BookCollection
{
    public int $id;

    #[Map(source: 'title')]
    public string $name;

    public string $isbn;
}
