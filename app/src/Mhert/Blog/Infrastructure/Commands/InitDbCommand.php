<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Commands;

use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;
use function file_get_contents;
use function str_replace;

final class InitDbCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'blog-app:init-db';

    /** @var string */
    private $ebAppEnv;
    /** @var ParameterBagInterface */
    private $params;

    /** @var DynamoDbClient */
    private $dynamoDbClient;

    public function __construct(
        string $ebAppEnv,
        ParameterBagInterface $params,
        DynamoDbClient $dynamoDbClient
    ) {
        parent::__construct();

        $this->ebAppEnv = $ebAppEnv;
        $this->params = $params;
        $this->dynamoDbClient = $dynamoDbClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = $this->params->get('kernel.project_dir');

        $dynamodbConfigFile = file_get_contents($projectDir . '/.ebextensions/dynamodb.config');
        if ($dynamodbConfigFile === false) {
            return 1;
        }

        $dynamodbConfigFile = str_replace('$EB_APP_ENV', $this->ebAppEnv, $dynamodbConfigFile);
        $postTableDefinition = Yaml::parse($dynamodbConfigFile)['Resources']['Post']['Properties'];

        $this->dynamoDbClient->createTable($postTableDefinition);

        return 0;
    }
}
