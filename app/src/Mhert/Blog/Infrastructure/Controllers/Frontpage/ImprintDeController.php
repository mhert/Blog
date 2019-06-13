<?php

declare(strict_types = 1);

namespace Mhert\Blog\Infrastructure\Controllers\Frontpage;

use Mhert\Blog\Infrastructure\Views\ImprintViewHtml;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ImprintDeController
{
    /** @var ImprintViewHtml */
    private $view;

    public function __construct(ImprintViewHtml $view)
    {
        $this->view = $view;
    }
    public function action(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view->render('de');
    }
}
