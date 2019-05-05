<?php

declare(strict_types = 1);

namespace App;

use Parsedown;

final class ParsedownMarkdownParser extends Parsedown
{
    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1])) {
            $level = 2;

            while (isset($Line['text'][$level]) and $Line['text'][$level] === '#') {
                $level++;
            }

            if ($level > 6) {
                return;
            }

            $text = trim($Line['text'], '# ');

            $Block = array(
                'element' => array(
                    'name' => 'h' . min(6, $level),
                    'text' => $text,
                    'handler' => 'line',
                ),
            );

            return $Block;
        }
    }
}
