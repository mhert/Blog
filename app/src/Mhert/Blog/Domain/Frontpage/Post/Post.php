<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

final class Post
{
    /** @var UuidInterface */
    private $id;
    /** @var DateTimeInterface */
    private $created;
    /** @var string */
    private $content;

    public function __construct(UuidInterface $id, DateTimeInterface $created, string $content)
    {
        $this->id = $id;
        $this->created = $created;
        $this->content = $content;
    }

    public function print(callable $printer): void
    {
        $printer($this->id, $this->created, $this->content);
    }
}
