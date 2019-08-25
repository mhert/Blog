<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\DynamoDb;
use function str_replace;

final class PostTableName
{
    const ENVIRONMENT_VARIABLE_NAME = '`{ "Ref" : "AWSEBEnvironmentName" }`';
    private string $ebAppEnv;

    public function __construct(string $ebAppEnv)
    {
        $this->ebAppEnv = $ebAppEnv;
    }

    public function replace(string $str): string
    {
        return str_replace(self::ENVIRONMENT_VARIABLE_NAME, $this->ebAppEnv, $str);
    }

    public function toString(): string
    {
        return sprintf('%s_Post', $this->ebAppEnv);
    }
}
