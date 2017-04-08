<?php namespace Litcanvas\Action;

use Litcanvas\Bolt\LCAction;

class IndexAction extends LCAction
{
    const PATH = '/';

    protected function main()
    {
        $r = $this->container->canvas->getAllHex();

        return $this->twig('page/index.twig')->render([]);
    }
}
