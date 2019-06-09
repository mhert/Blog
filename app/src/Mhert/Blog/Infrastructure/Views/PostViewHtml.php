<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Views;

use DateTimeInterface;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Infrastructure\ParsedownMarkdownParser;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

final class PostViewHtml
{
    /** @var TwigEnvironment */
    private $twigEnvironment;
    /** @var ParsedownMarkdownParser */
    private $markdownParser;

    public function __construct(
        TwigEnvironment $twigEnvironment,
        ParsedownMarkdownParser $markdownParser
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->markdownParser = $markdownParser;
    }

    public function render(Post $post): Response
    {
        $page = [
            'post' => $this->adjustPost($post)
        ];

        return new Response(
            $this->twigEnvironment->render('post.html.twig', ['page' => $page])
        );
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
