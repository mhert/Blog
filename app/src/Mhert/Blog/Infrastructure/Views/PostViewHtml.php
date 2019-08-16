<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Views;

use DateTimeInterface;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\Slug;
use Mhert\Blog\Infrastructure\ParsedownMarkdownParser;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ramsey\Uuid\UuidInterface;
use Teapot\StatusCode\Http;
use Twig\Environment as TwigEnvironment;

final class PostViewHtml
{
    private TwigEnvironment $twigEnvironment;
    private ParsedownMarkdownParser $markdownParser;
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;

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
                Slug $slug,
                DateTimeInterface $created,
                string $content
            ) use (
                &$result
            ): void {
                $result = [
                    'content' => $this->markdownParser->parse($content),
                    'created' => $created->format(DateTimeInterface::ATOM),
                ];
            }
        );

        return $result;
    }
}
