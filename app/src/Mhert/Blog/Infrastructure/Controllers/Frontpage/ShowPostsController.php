<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Infrastructure\Views\PostsViewHtml;
use Symfony\Component\HttpFoundation\Response;

final class ShowPostsController
{
    /** @var PostsViewHtml */
    private $view;
    /** @var PostRepository */
    private $postRepository;

    public function __construct(PostsViewHtml $view, PostRepository $postRepository)
    {
        $this->view = $view;
        $this->postRepository = $postRepository;
    }

    public function action(): Response
    {
        return $this->view->render(
            $this->postRepository->findPostsByOffset(0, 10)
        );
    }
}
