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

final class ImprintViewHtml
{
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;
    private TwigEnvironment $twig;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        TwigEnvironment $twig
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->twig = $twig;
    }

    public function render(): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Http::OK)
            ->withBody(
                $this->streamFactory->createStream(
                    $this->twig->render(
                        'base.html.twig',
                        ['body' => $this->twig->render('views/imprint.html.twig')]
                    )
                )
            );
    }
}
