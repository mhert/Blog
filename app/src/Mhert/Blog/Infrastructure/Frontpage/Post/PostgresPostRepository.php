<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Frontpage\Post;

use DateTimeImmutable;
use DateTimeInterface;
use Mhert\Blog\Domain\Frontpage\Post\FactoryBasedPostList;
use Mhert\Blog\Domain\Frontpage\Post\Post;
use Mhert\Blog\Domain\Frontpage\Post\PostList;
use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use Mhert\Blog\Domain\Frontpage\Post\Slug;
use PDO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;

final class PostgresPostRepository implements PostRepository
{
    /** @var PDO */
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findPostsByOffset(int $offset, int $numberOfPosts): PostList
    {
        $statement = $this->db->prepare("SELECT * FROM posts LIMIT :numberOfPosts OFFSET :offset;");

        $statement->bindValue(':offset', (string)$offset, PDO::PARAM_INT);
        $statement->bindValue(':numberOfPosts', (string)$numberOfPosts, PDO::PARAM_INT);
        $statement->execute();

        return new FactoryBasedPostList(static function () use ($statement) {
            $rawPost = $statement->fetch(PDO::FETCH_ASSOC);

            if ($rawPost !== false) {
                return self::postByResult($rawPost);
            }

            return null;
        });
    }

    public function findPostById(UuidInterface $id): Post
    {
        $statement = $this->db->prepare("SELECT * FROM posts WHERE id = :id;");

        $statement->bindValue(':id', $id->toString(), PDO::PARAM_STR);
        $statement->execute();

        $rawPost = $statement->fetch(PDO::FETCH_ASSOC);

        return self::postByResult($rawPost);
    }

    public function findPostBySlug(Slug $slug): Post
    {
        $statement = $this->db->prepare("SELECT * FROM posts WHERE slug = :slug;");

        $statement->bindValue(':slug', $slug->toString(), PDO::PARAM_STR);
        $statement->execute();

        $rawPost = $statement->fetch(PDO::FETCH_ASSOC);

        return self::postByResult($rawPost);
    }

    /**
     * @param mixed[] $rawPost
     */
    private static function postByResult(array $rawPost): Post
    {
        $id = Uuid::fromString($rawPost['id']);
        $created = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $rawPost['created']);
        $content = $rawPost['content'];

        if (!$created instanceof DateTimeInterface) {
            throw new RuntimeException('Could not parse created date');
        }

        return new Post(
            $id,
            $created,
            $content
        );
    }
}
