<?php

declare(strict_types=1);

namespace App\ApiResource;

use App\Entity\Book as BookEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: BookEntity::class)]
final class BookPatch
{
    #[Map(target: 'title')]
    public string $name;

    public string $description;
}
