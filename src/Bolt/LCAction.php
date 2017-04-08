<?php namespace Litcanvas\Bolt;

use Lit\Bolt\BoltAction;
use Litcanvas\Twig\TwigView;

/**
 * Class LCAction
 * @package Litcanvas\Bolt
 *
 * @property LCContainer $container
 */
abstract class LCAction extends BoltAction
{
    const METHOD = 'GET';
    const PATH = '/';

    protected function twig($name)
    {
        /**
         * @var TwigView $view
         */
        $view = $this->container->instantiate(TwigView::class, [
            'name' => $name,
            'data' => [],
        ]);

        return $this->attachView($view);
    }
}
