<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class PostId
{
    private UuidInterface $id;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function fromNumeric(string $id): self
    {
        return new self(
            Uuid::fromInteger($id)
        );
    }

    public static function fromString(string $id): self
    {
        return new self(
            Uuid::fromString($id)
        );
    }

    public function toNumeric(): string
    {
        return (string)$this->id->getInteger();
    }

    public function toString(): string
    {
        return $this->id->toString();
    }
}
