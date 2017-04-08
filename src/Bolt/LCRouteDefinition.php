<?php namespace Litcanvas\Bolt;

use FastRoute\RouteCollector;
use Lit\Bolt\BoltRouteDefinition;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LCRouteDefinition extends BoltRouteDefinition
{
    public function __invoke(RouteCollector $routeCollector)
    {
        $files = (new Finder())
            ->files()
            ->in(__DIR__ . '/../Action')
            ->name('*Action.php');
        foreach ($files as $file) {
            /**
             * @var SplFileInfo $file
             * @var LCAction $class
             */
            $class = sprintf('Litcanvas\\Action\\%s',
                substr(str_replace(DIRECTORY_SEPARATOR, '\\', $file->getRelativePathname()), 0, -4));//cut -4 .php
            $routeCollector->addRoute(strtoupper($class::METHOD), $class::PATH, $class);
        }
    }
}
