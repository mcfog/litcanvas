<?php namespace Litcanvas\Bolt;

use Lit\Bolt\BoltContainer;
use Lit\Bolt\BoltRouteDefinition;
use Lit\Nexus\Cache\CacheKeyValue;
use Litcanvas\Canvas;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Stash\Driver\FileSystem;
use Stash\Interfaces\DriverInterface;
use Stash\Pool;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

/**
 * Class LCContainer
 * @package Litcanvas
 *
 * @property CacheKeyValue $localCache
 * @property LoggerInterface $logger
 * @property Canvas $canvas
 */
class LCContainer extends BoltContainer
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this
            //bolt
            ->alias(LCRouteDefinition::class, BoltRouteDefinition::class)
            //stash
            ->alias(FileSystem::class, DriverInterface::class)
            ->alias(CacheKeyValue::class, 'localCache',
                [
                    function () {
                        /**
                         * @var Pool $pool
                         */
                        $pool = $this->produce(Pool::class);
                        $this->events->addListener(LCApp::EVENT_AFTER_LOGIC, [$pool, 'commit']);

                        return $pool;
                    },
                    86400
                ])
            //monolog
            ->alias(Logger::class, 'logger', [
                'name' => 'default',
                'handlers' => function () {
                    return array_map([$this->stubResolver, 'resolve'], $this->config('[log]', []));
                },
            ])
            //twig
            ->alias(Twig_Loader_Filesystem::class, Twig_LoaderInterface::class, [
                'paths' => [
                    __DIR__ . '/../../templates'
                ],
            ])
            //Litcanvase
            ->alias(Canvas::class, 'canvas');

        foreach ($this->config('[container]', []) as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function config($key, $default = null)
    {
        return $this->get($this['config'], $key, $default);
    }
}
