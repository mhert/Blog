<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Commands;

use Aws\DynamoDb\DynamoDbClient;
use Mhert\Blog\Infrastructure\DynamoDb\PostTableName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;
use function file_get_contents;

final class InitDbCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'blog-app:init-db';

    private DynamoDbClient $dynamoDbClient;
    private PostTableName $tableName;
    private ParameterBagInterface $params;

    public function __construct(
        PostTableName $tableName,
        ParameterBagInterface $params,
        DynamoDbClient $dynamoDbClient
    ) {
        parent::__construct();

        $this->tableName = $tableName;
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

        $dynamodbConfigFile = $this->tableName->replace($dynamodbConfigFile);
        $postTableDefinition = Yaml::parse($dynamodbConfigFile)['Resources']['Post']['Properties'];

        $this->dynamoDbClient->createTable($postTableDefinition);

        return 0;
    }
}
