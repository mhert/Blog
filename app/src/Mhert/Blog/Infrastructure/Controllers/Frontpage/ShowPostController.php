<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Infrastructure\Views\PostViewHtml;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

final class ShowPostController
{
    /** @var PostViewHtml */
    private $view;
    /** @var PostRepository */
    private $postRepository;

    public function __construct(PostViewHtml $view, PostRepository $postRepository)
    {
        $this->view = $view;
        $this->postRepository = $postRepository;
    }

    public function action(string $id): Response
    {
        $id = Uuid::fromString($id);

        return $this->view->render(
            $this->postRepository->findPostById($id)
        );
    }
}
