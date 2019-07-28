<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class InMemoryPostRepository implements PostRepository
{
    public function findPostsByOffset(int $offset, int $numberOfPosts): PostList
    {
        return new ArrayBasedPostList([]);
    }

    public function findPostById(UuidInterface $id): Post
    {
        return new Post(
            $id,
            new DateTimeImmutable('now'),
            ''
        );
    }
}