<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Views;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Teapot\StatusCode\Http;
use Twig\Environment as TwigEnvironment;
use function sprintf;

final class ImprintViewHtml
{
    /** @var TwigEnvironment */
    private $twigEnvironment;
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(
        TwigEnvironment $twigEnvironment,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }


    public function render(string $language): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(Http::OK)
            ->withBody($this->streamFactory->createStream(
                $this->twigEnvironment->render(sprintf('imprint-%s.html.twig', $language))
            ));
    }
}
