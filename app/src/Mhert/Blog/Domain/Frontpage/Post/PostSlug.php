<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

final class PostSlug
{
    private string $slug;

    private function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public static function fromString(string $slug): self
    {
        return new self($slug);
    }

    public function toString(): string
    {
        return $this->slug;
    }
}
