<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Elements;

use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostList;
use Mhert\Blog\Domain\Frontpage\Post\PostListRenderer;
use Mhert\Blog\Domain\Frontpage\Post\PostRenderer;
use Twig\Environment as TwigEnvironment;
use function array_map;
use function iterator_to_array;

final class TwigPostListRenderer implements PostListRenderer
{
    private TwigEnvironment $twig;
    private PostRenderer $postRenderer;

    public function __construct(TwigEnvironment $twigEnvironment, PostRenderer $postRenderer)
    {
        $this->twig = $twigEnvironment;
        $this->postRenderer = $postRenderer;
    }

    public function render(PostList $posts): string
    {
        $renderedPosts = array_map(function (Post $post): string {
            return $post->render($this->postRenderer);
        }, iterator_to_array($posts));

        return $this->twig->render('elements/post-list.html.twig', ['posts' => $renderedPosts]);
    }
}
