<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\DynamoDb;
use function str_replace;

final class PostTableName
{
    private string $ebAppEnv;

    public function __construct(string $ebAppEnv)
    {
        $this->ebAppEnv = $ebAppEnv;
    }

    public function replace(string $str): string
    {
        return str_replace('$EB_APP_ENV', $this->ebAppEnv, $str);
    }

    public function toString(): string
    {
        return $this->replace('$EB_APP_ENV_Post');
    }
}
