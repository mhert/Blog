<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use Generator;
use IteratorAggregate;

final class FactoryBasedPostList implements IteratorAggregate, PostList
{
    /** @var callable */
    private $postFactory;

    public function __construct(callable $postFactory)
    {
        $this->postFactory = $postFactory;
    }

    public function getIterator(): Generator
    {
        while ($post = ($this->postFactory)()) {
            return yield $post;
        }
    }
}
