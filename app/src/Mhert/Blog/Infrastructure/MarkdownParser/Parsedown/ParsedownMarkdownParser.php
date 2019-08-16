<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\MarkdownParser\Parsedown;

use Mhert\Blog\Infrastructure\MarkdownParser\MarkdownParser;
use Parsedown;

final class ParsedownMarkdownParser implements MarkdownParser
{
    private Parsedown $parser;

    public function __construct(Parsedown $parser)
    {
        $this->parser = $parser;
    }

    public function parse(string $text): string
    {
        return $this->parser->parse($text);
    }
}
