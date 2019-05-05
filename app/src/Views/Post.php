<?php

declare(strict_types = 1);

namespace App\Views;

use DateTimeInterface;

final class Post
{
    /** @var string */
    private $id;
    /** @var DateTimeInterface */
    private $created;
    /** @var string */
    private $content;

    public function __construct(string $id, DateTimeInterface $created, string $content)
    {
        $this->id = $id;
        $this->created = $created;
        $this->content = $content;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function created(): DateTimeInterface
    {
        return $this->created;
    }

    public function content(): string
    {

        return $this->content;
    }
}
