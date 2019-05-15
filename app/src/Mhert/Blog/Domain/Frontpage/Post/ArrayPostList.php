<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use Iterator;
use function array_key_exists;

final class ArrayPostList implements Iterator, PostList
{
    /** @var int */
    private $position = 0;
    /** @var Post[] */
    private $posts = [];

    /**
     * @param Post[] $posts
     */
    public function __construct(array $posts)
    {
        foreach ($posts as $post) {
            $this->addPost($post);
        }
    }

    private function addPost(Post $post): void
    {
        $this->posts[] = $post;
    }

    public function current(): Post
    {
        return $this->posts[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return array_key_exists($this->position, $this->posts);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
