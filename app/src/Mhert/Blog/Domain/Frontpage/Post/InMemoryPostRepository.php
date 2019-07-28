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
            new Slug(''),
            new DateTimeImmutable('now'),
            ''
        );
    }

    public function findPostBySlug(Slug $slug): Post
    {
        return new Post(
            Uuid::uuid4(),
            new Slug(''),
            new DateTimeImmutable('now'),
            ''
        );
    }
}