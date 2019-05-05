<?php

declare(strict_types = 1);

namespace App\Views;

use DateTimeInterface;
use App\ParsedownMarkdownParser;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

final class PostsViewHtml
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

    /**
     * @param Post[] $posts
     */
    public function render(array $posts): Response
    {
        $page = [
            'posts' => $this->adjustPosts($posts)
        ];

        return new Response(
            $this->twigEnvironment->render('posts.html.twig', ['page' => $page])
        );
    }

    /**
     * @param Post[] $posts
     * @return mixed[]
     */
    private function adjustPosts(array $posts): array
    {
        return array_map(function (Post $post): array {
            return [
                'content' => $this->markdownParser->parse($post->content()),
                'created' => $post->created()->format(DateTimeInterface::ISO8601),
            ];
        }, $posts);
    }
}
