<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use DateTimeImmutable;
use InvalidArgumentException;
use function sprintf;

final class PostCreated
{
    private DateTimeImmutable $created;

    private function __construct(DateTimeImmutable $created)
    {
        $this->created = $created;
    }

    public static function fromString(string $created): self
    {
        $createdParsed = DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $created);

        if (!$createdParsed instanceof DateTimeImmutable) {
            throw new InvalidArgumentException(
                sprintf('Could not parse "%s" as Date with format "%s"', $created, DateTimeImmutable::ATOM)
            );
        }
        
        return new self(
            $createdParsed
        );
    }

    public function formatPrintable(): string
    {
        return $this->created->format(DateTimeImmutable::ATOM);
    }
}
