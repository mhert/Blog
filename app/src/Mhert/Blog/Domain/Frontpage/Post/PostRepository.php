<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

interface PostRepository
{
    public function findPostsByOffset(int $offset, int $numberOfPosts): PostList;

    public function findPostById(PostId $id): ?Post;

    public function findPostBySlug(PostSlug $slug): ?Post;
}
