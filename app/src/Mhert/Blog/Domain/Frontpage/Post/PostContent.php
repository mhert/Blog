<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

use Mhert\Blog\Infrastructure\MarkdownParser\MarkdownParser;

final class PostContent
{
    private string $content;

    private function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function fromString(string $content): self
    {
        return new self($content);
    }

    public function toString(): string
    {
        return $this->content;
    }

    public function parse(MarkdownParser $markdownParser): string
    {
        return $markdownParser->parse($this->content);
    }
}
