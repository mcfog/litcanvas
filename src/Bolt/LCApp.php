<?php namespace Litcanvas\Bolt;

use Lit\Bolt\BoltApp;
use Lit\Bolt\BoltContainer;
use Litcanvas\ErrorHandler;

class LCApp extends BoltApp
{
    public function __construct(BoltContainer $container)
    {
        parent::__construct($container);

//        $this->pipe($container->produce(ErrorHandler::class));
    }
}
