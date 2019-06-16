<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure;

use Parsedown;

final class ParsedownMarkdownParser extends Parsedown
{
    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1])) {
            $level = 1;

            while (isset($Line['text'][$level]) && $Line['text'][$level] === '#') {
                $level++;
            }

            if ($level > 3) {
                return;
            }

            $text = trim($Line['text'], '# ');

            $Block = array(
                'element' => array(
                    'name' => 'h' . min(3, $level + 1),
                    'text' => $text,
                    'handler' => 'line',
                ),
            );

            return $Block;
        }
    }
}
