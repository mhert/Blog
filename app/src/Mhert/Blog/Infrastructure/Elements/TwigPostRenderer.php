<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Elements;

use Mhert\Blog\Domain\Frontpage\Post\PostContent;
use Mhert\Blog\Domain\Frontpage\Post\PostCreated;
use Mhert\Blog\Domain\Frontpage\Post\PostId;
use Mhert\Blog\Domain\Frontpage\Post\PostRenderer;
use Mhert\Blog\Domain\Frontpage\Post\PostSlug;
use Mhert\Blog\Domain\Frontpage\Post\PostTitle;
use Mhert\Blog\Infrastructure\MarkdownParser\MarkdownParser;
use Twig\Environment as TwigEnvironment;

final class TwigPostRenderer implements PostRenderer
{
    private TwigEnvironment $twig;
    private MarkdownParser $markdownParser;

    public function __construct(TwigEnvironment $twigEnvironment, MarkdownParser $markdownParser)
    {
        $this->twig = $twigEnvironment;
        $this->markdownParser = $markdownParser;
    }

    public function render(
        PostId $id,
        PostSlug $slug,
        PostCreated $created,
        PostTitle $title,
        PostContent $content
    ): string {
        $renderedPost = [
            'id' => $id->toString(),
            'slug' => $slug->toString(),
            'content' => $this->markdownParser->parse($content->parse($this->markdownParser)),
            'title' => $title->toString(),
            'created' => $created->formatPrintable(),
        ];

        return $this->twig->render('elements/post.html.twig', ['post' => $renderedPost]);
    }
}
