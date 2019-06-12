<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Views;

use DateTimeInterface;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Infrastructure\ParsedownMarkdownParser;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ramsey\Uuid\UuidInterface;
use Teapot\StatusCode\Http;
use Twig\Environment as TwigEnvironment;

final class PostViewHtml
{
    /** @var TwigEnvironment */
    private $twigEnvironment;
    /** @var ParsedownMarkdownParser */
    private $markdownParser;
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(
        TwigEnvironment $twigEnvironment,
        ParsedownMarkdownParser $markdownParser,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->markdownParser = $markdownParser;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function render(Post $post): ResponseInterface
    {
        $page = [
            'post' => $this->adjustPost($post)
        ];

        return $this->responseFactory
            ->createResponse(Http::OK)
            ->withBody($this->streamFactory->createStream(
                $this->twigEnvironment->render('post.html.twig', ['page' => $page])
            ));
    }

    /**
     * @return mixed[]
     */
    private function adjustPost(Post $post): array
    {
        $result = [];

        $post->print(
            function (
                UuidInterface $id,
                DateTimeInterface $created,
                string $content
            ) use (
                &$result
            ): void {
                $result = [
                    'content' => $this->markdownParser->parse($content),
                    'created' => $created->format(DateTimeInterface::ISO8601),
                ];
            }
        );

        return $result;
    }
}
