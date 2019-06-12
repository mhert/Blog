<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Infrastructure\Views\PostViewHtml;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

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

    public function action(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $id = Uuid::fromString($id);

        return $this->view->render(
            $this->postRepository->findPostById($id)
        );
    }
}
