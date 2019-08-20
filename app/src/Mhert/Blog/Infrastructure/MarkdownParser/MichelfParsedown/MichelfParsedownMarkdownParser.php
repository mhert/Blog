<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\MarkdownParser\MichelfParsedown;

use Mhert\Blog\Infrastructure\MarkdownParser\MarkdownParser;
use Michelf\MarkdownExtra;

final class MichelfParsedownMarkdownParser implements MarkdownParser
{
    public function parse(string $text): string
    {
        return MarkdownExtra::defaultTransform($text);
    }
}
