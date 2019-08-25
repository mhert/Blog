<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Views;

use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Teapot\StatusCode\Http;
use Twig\Environment as TwigEnvironment;

final class PostViewHtml
{
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;
    private TwigEnvironment $twig;
    private PostRenderer $postRenderer;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        TwigEnvironment $twig,
        PostRenderer $postRenderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->twig = $twig;
        $this->postRenderer = $postRenderer;
    }

    public function render(Post $post): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Http::OK)
            ->withBody(
                $this->streamFactory->createStream(
                    $this->twig->render(
                        'base.html.twig',
                        ['body' => $post->render($this->postRenderer)]
                    )
                )
            );
    }
}
