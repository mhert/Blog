<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

final class Post
{
    private PostId $id;
    private PostSlug $slug;
    private PostCreated $created;
    private PostContent $content;

    public function __construct(
        PostId $id,
        PostSlug $slug,
        PostCreated $created,
        PostContent $content
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->created = $created;
        $this->content = $content;
    }

    public function render(PostRenderer $renderer): string
    {
        return $renderer->render(
            $this->id,
            $this->slug,
            $this->created,
            $this->content,
        );
    }
}
