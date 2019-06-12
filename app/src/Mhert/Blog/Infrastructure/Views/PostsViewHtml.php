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

final class PostsViewHtml
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

    /**
     * @param Post[] $posts
     */
    public function render(iterable $posts): ResponseInterface
    {
        $page = [
            'posts' => $this->adjustPosts($posts)
        ];

        return $this->responseFactory
            ->createResponse(Http::OK)
            ->withBody($this->streamFactory->createStream(
                $this->twigEnvironment->render('posts.html.twig', ['page' => $page])
            ));
    }

    /**
     * @param Post[] $posts
     * @return mixed[]
     */
    private function adjustPosts(iterable $posts): iterable
    {
        foreach ($posts as $post) {
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

            return yield $result;
        }

        return [];
    }
}
