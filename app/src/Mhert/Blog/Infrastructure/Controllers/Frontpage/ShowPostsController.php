<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Infrastructure\Views\PostsViewHtml;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ShowPostsController
{
    private PostsViewHtml $view;
    private PostRepository $postRepository;

    public function __construct(PostsViewHtml $view, PostRepository $postRepository)
    {
        $this->view = $view;
        $this->postRepository = $postRepository;
    }

    public function action(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view->render(
            $this->postRepository->findPostsByOffset(0, 10)
        );
    }
}
