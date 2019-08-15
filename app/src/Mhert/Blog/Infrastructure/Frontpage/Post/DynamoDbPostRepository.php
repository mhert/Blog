<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Frontpage\Post;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use DateTimeImmutable;
use DateTimeInterface;
use Mhert\Blog\Domain\Frontpage\Post\ArrayBasedPostList;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostList;
use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Domain\Frontpage\Post\Slug;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use function array_map;

final class DynamoDbPostRepository implements PostRepository
{
    /** @var string */
    private $ebAppEnv;
    /** @var DynamoDbClient */
    private $dynamoDbClient;
    /** @var Marshaler */
    private $marshaler;

    public function __construct(
        string $ebAppEnv,
        DynamoDbClient $dynamoDbClient
    ) {
        $this->ebAppEnv = $ebAppEnv;
        $this->dynamoDbClient = $dynamoDbClient;
        $this->marshaler = new Marshaler();
    }

    public function findPostsByOffset(int $offset, int $numberOfPosts): PostList
    {
        $params = [
            'TableName' => $this->tableName(),
        ];

        $result = $this->dynamoDbClient->scan($params)->toArray();

        return new ArrayBasedPostList(array_map(static function (array $rawPost) {
                return self::postByResult($rawPost);
        }, $result['Items']));
    }

    public function findPostById(UuidInterface $id): Post
    {
        $params = [
            'TableName' => $this->tableName(),
            'ExpressionAttributeNames' => [
                '#id' => 'id',
            ],
            'ExpressionAttributeValues' => $this->marshaler->marshalItem([
                ':id' => $this->marshaler->number($id->getInteger()),
            ]),
            'KeyConditionExpression' => '#id = :id'
        ];
        $result = $this->dynamoDbClient->query($params)->toArray();
        return self::postByResult($result['Items'][0]);
    }

    public function findPostBySlug(Slug $slug): Post
    {
        $params = [
            'TableName' => $this->tableName(),
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
        return self::postByResult($result['Items'][0]);
    }

    /**
     * @param mixed[] $rawPost
     */
    private static function postByResult(array $rawPost): Post
    {
        $id = Uuid::fromInteger($rawPost['id']['N']);
        $slug = new Slug($rawPost['slug']['S']);
        $created = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $rawPost['created']['S']);
        $content = $rawPost['content']['S'];

        if (!$created instanceof DateTimeInterface) {
            throw new RuntimeException('Could not parse created date');
        }

        return new Post(
            $id,
            $slug,
            $created,
            $content
        );
    }

    private function tableName(): string
    {
        return $this->ebAppEnv . '_Post';
    }
}
