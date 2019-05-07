<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers;

use DateTimeImmutable;
use Mhert\Blog\Infrastructure\Views\Post;
use Mhert\Blog\Infrastructure\Views\PostsViewHtml;
use Symfony\Component\HttpFoundation\Response;

final class IndexController
{
    /** @var PostsViewHtml */
    private $view;

    public function __construct(PostsViewHtml $view)
    {
        $this->view = $view;
    }

    public function action(): Response
    {
        $posts = [
            new Post(
                '123-456',
                new DateTimeImmutable('now'),
                ''
            )
        ];

        return $this->view->render($posts);
    }
}
