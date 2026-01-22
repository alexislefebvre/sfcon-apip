<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Dto\CreateBook;
use App\Dto\DiscountBook;
use App\Dto\UpdateBook;
use App\Entity\Book as BookEntity;
use App\State\BookProvider;
use App\State\DiscountBookProcessor;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ApiResource(
    shortName: 'Book',
    provider: BookProvider::class,
    jsonStream: true,
    operations: [
        new Get(
            uriTemplate: '/books/{id}',
            uriVariables: ['id'],
        ),
        new Patch(
            uriTemplate: '/books/{id}',
            uriVariables: ['id'],
            input: UpdateBook::class,
        ),
        new Post(
            uriTemplate: '/books',
            input: CreateBook::class,
        ),
        new Post(
            uriTemplate: '/books/{id}/discount',
            uriVariables: ['id'],
            input: DiscountBook::class,
            processor: DiscountBookProcessor::class,
            status: 200,
        ),
    ],
)]
#[Map(source: BookEntity::class)]
final class Book
{
    public int $id;

    #[Map(source: 'title')]
    public string $name;

    public string $description;

    public string $isbn;

    #[Map(transform: [self::class, 'formatPrice'])]
    public string $price;

    public static function formatPrice(mixed $price, object $source): int|string
    {
        if ($source instanceof BookEntity) {
            return number_format($price / 100, 2).'$';
        }

        if ($source instanceof self) {
            return 100 * (int) str_replace('$', '', $price);
        }

        throw new \LogicException(\sprintf('Unexpected "%s" source.', $source::class));
    }
}
