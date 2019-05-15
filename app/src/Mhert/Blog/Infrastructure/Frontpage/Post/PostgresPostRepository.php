<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Frontpage\Post;

use Mhert\Blog\Domain\Frontpage\Post\PostList;
use Mhert\Blog\Domain\Frontpage\Post\PostRepository;
use PDO;
use PDOStatement;
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
        $posts = $this->db->query("SELECT * FROM posts;");

        if (!$posts instanceof PDOStatement) {
            throw new RuntimeException("Could not create Statement");
        }

        return new PdoStatementPostList($posts);
    }
}
