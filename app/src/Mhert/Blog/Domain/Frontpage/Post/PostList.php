<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use Traversable;

interface PostList extends Traversable
{
    public function render(PostListRenderer $renderer): string;
}
