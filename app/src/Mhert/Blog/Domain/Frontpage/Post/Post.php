<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

final class Post
{
    private UuidInterface $id;
    private Slug $slug;
    private DateTimeInterface $created;
    private string $content;

    public function __construct(
        UuidInterface $id,
        Slug $slug,
        DateTimeInterface $created,
        string $content
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->created = $created;
        $this->content = $content;
    }

    public function print(callable $printer): void
    {
        $printer($this->id, $this->slug, $this->created, $this->content);
    }
}
