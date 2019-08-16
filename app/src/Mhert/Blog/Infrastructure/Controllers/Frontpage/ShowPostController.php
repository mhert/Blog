<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Domain\Frontpage\Post\Slug;
use Mhert\Blog\Infrastructure\Views\PostViewHtml;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ShowPostController
{
    private PostViewHtml $view;
    private PostRepository $postRepository;

    public function __construct(PostViewHtml $view, PostRepository $postRepository)
    {
        $this->view = $view;
        $this->postRepository = $postRepository;
    }

    public function action(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        $slug = new Slug($slug);

        return $this->view->render(
            $this->postRepository->findPostBySlug($slug)
        );
    }
}
