<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Frontpage\Post;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use IteratorAggregate;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostList;
use PDO;
use PDOStatement;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class PdoStatementPostList implements IteratorAggregate, PostList
{
    /** @var PDOStatement */
    private $statement;

    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    public function getIterator(): Generator
    {
        $rawPost = $this->statement->fetch(PDO::FETCH_ASSOC);

        $id = Uuid::fromString($rawPost['id']);
        $created = DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $rawPost['created']);
        $content = $rawPost['content'];

        if (!$created instanceof DateTimeInterface) {
            throw new RuntimeException('Could not parse created date');
        }

        return yield new Post(
            $id,
            $created,
            $content
        );
    }
}
