<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

interface PostRenderer
{
    public function render(
        PostId $id,
        PostSlug $slug,
        PostCreated $created,
        PostContent $content
    ): string;
}
