<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

interface PostListRenderer
{
    public function render(
        PostList $postList
    ): string;
}
