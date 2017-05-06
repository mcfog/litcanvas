<?php namespace Litcanvas\Action;

use Litcanvas\Bolt\LCAction;

class IndexAction extends LCAction
{
    const PATH = '/';

    protected function main()
    {
        return $this->twig('page/index.twig')->render([]);
    }
}
