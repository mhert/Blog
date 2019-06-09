<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use Ramsey\Uuid\UuidInterface;

interface PostRepository
{
    public function findPostsByOffset(int $offset, int $numberOfPosts): PostList;

    public function findPostById(UuidInterface $id): Post;
}
