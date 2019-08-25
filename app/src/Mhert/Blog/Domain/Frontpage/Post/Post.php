<?php

declare(strict_types = 1);

namespace Mhert\Blog\Domain\Frontpage\Post;

final class Post
{
    private PostId $id;
    private PostSlug $slug;
    private PostCreated $created;
    private PostTitle $title;
    private PostContent $content;

    public function __construct(
        PostId $id,
        PostSlug $slug,
        PostCreated $created,
        PostTitle $title,
        PostContent $content
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->created = $created;
        $this->title = $title;
        $this->content = $content;
    }

    public function render(PostRenderer $renderer): string
    {
        return $renderer->render(
            $this->id,
            $this->slug,
            $this->created,
            $this->title,
            $this->content,
        );
    }
}
