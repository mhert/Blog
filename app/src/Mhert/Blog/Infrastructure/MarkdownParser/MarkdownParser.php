<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\MarkdownParser;

interface MarkdownParser
{
    public function parse(string $text): string;
}
