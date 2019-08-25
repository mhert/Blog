<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\DynamoDb\Frontpage\Post;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Mhert\Blog\Domain\Frontpage\Post\ArrayBasedPostList;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostContent;
use Mhert\Blog\Domain\Frontpage\Post\PostCreated;
use Mhert\Blog\Domain\Frontpage\Post\PostId;
use Mhert\Blog\Domain\Frontpage\Post\PostList;
use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Domain\Frontpage\Post\PostSlug;
use Mhert\Blog\Domain\Frontpage\Post\PostTitle;
use Mhert\Blog\Infrastructure\DynamoDb\PostTableName;
use RuntimeException;
use function array_map;

final class DynamoDbPostRepository implements PostRepository
{
    private DynamoDbClient $dynamoDbClient;
    private PostTableName $tableName;
    private Marshaler $marshaler;

    public function __construct(
        PostTableName $tableName,
        DynamoDbClient $dynamoDbClient
    ) {
        $this->tableName = $tableName;
        $this->dynamoDbClient = $dynamoDbClient;
        $this->marshaler = new Marshaler();
    }

    public function findPostsByOffset(int $offset, int $numberOfPosts): PostList
    {
        $params = [
            'TableName' => $this->tableName->toString(),
        ];

        $result = $this->dynamoDbClient->scan($params)->toArray();

        return new ArrayBasedPostList(array_map(static function (array $rawPost) {
                return self::postByResult($rawPost);
        }, $result['Items']));
    }

    public function findPostById(PostId $id): ?Post
    {
        $params = [
            'TableName' => $this->tableName->toString(),
            'ExpressionAttributeNames' => [
                '#id' => 'id',
            ],
            'ExpressionAttributeValues' => $this->marshaler->marshalItem([
                ':id' => $this->marshaler->number($id->toNumeric()),
            ]),
            'KeyConditionExpression' => '#id = :id'
        ];
        $result = $this->dynamoDbClient->query($params)->toArray();

        if ($result['Count'] === 0) {
            return null;
        }

        if ($result['Count'] > 1) {
            throw new RuntimeException('Too many results');
        }

        return self::postByResult($result['Items'][0]);
    }

    public function findPostBySlug(PostSlug $slug): ?Post
    {
        $params = [
            'TableName' => $this->tableName->toString(),
            'IndexName' => 'slug',
            'ExpressionAttributeNames' => [
                '#slug' => 'slug',
            ],
            'ExpressionAttributeValues' => $this->marshaler->marshalItem([
                ':slug' => $slug->toString(),
            ]),
            'KeyConditionExpression' => '#slug = :slug'
        ];
        $result = $this->dynamoDbClient->query($params)->toArray();

        if ($result['Count'] === 0) {
            return null;
        }

        if ($result['Count'] > 1) {
            throw new RuntimeException('Too many results');
        }

        return self::postByResult($result['Items'][0]);
    }

    /**
     * @param mixed[] $rawPost
     */
    private static function postByResult(array $rawPost): Post
    {
        $id = PostId::fromNumeric($rawPost['id']['N']);
        $slug = PostSlug::fromString($rawPost['slug']['S']);
        $created = PostCreated::fromString($rawPost['created']['S']);
        $headline = PostTitle::fromString($rawPost['title']['S']);
        $content = PostContent::fromString($rawPost['content']['S']);

        return new Post(
            $id,
            $slug,
            $created,
            $headline,
            $content
        );
    }
}
