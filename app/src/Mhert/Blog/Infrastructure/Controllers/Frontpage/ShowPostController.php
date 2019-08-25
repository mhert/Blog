<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Domain\Frontpage\Post\PostSlug;
use Mhert\Blog\Infrastructure\Views\PostViewHtml;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Teapot\StatusCode\Http;

final class ShowPostController
{
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;
    private PostViewHtml $view;
    private PostRepository $postRepository;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        PostViewHtml $view,
        PostRepository $postRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->view = $view;
        $this->postRepository = $postRepository;
    }

    public function action(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        $slug = PostSlug::fromString($slug);

        $post = $this->postRepository->findPostBySlug($slug);

        if (!$post instanceof Post) {
            return $this->responseFactory
                ->createResponse(Http::OK)
                ->withBody(
                    $this->streamFactory->createStream(
                        'not found'
                    )
                );
        }

        return $this->view->render($post);
    }
}
