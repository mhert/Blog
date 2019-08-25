<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Views;

use Mhert\Blog\Domain\Frontpage\Post\PostList;
use Mhert\Blog\Domain\Frontpage\Post\PostListRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Teapot\StatusCode\Http;
use Twig\Environment as TwigEnvironment;

final class PostsViewHtml
{
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;
    private TwigEnvironment $twig;
    private PostListRenderer $postListRenderer;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        TwigEnvironment $twig,
        PostListRenderer $postListRenderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->twig = $twig;
        $this->postListRenderer = $postListRenderer;
    }

    public function render(PostList $posts): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Http::OK)
            ->withBody(
                $this->streamFactory->createStream(
                    $this->twig->render(
                        'base.html.twig',
                        ['body' => $posts->render($this->postListRenderer)]
                    )
                )
            );
    }
}
