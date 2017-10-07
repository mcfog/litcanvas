<?php namespace Litcanvas\Action;

use Litcanvas\Bolt\LCAction;
use Psr\Http\Message\ResponseInterface;

class IndexAction extends LCAction
{
    const PATH = '/';

    protected function main(): ResponseInterface
    {
        return $this->twig('page/index.twig')->render([]);
    }
}
