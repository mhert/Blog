<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

final class Slug
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
