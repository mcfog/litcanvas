<?php namespace Litcanvas\Bolt;

use Lit\Air\Configurator;
use Lit\Air\Factory;
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
    public function __construct(?array $config = null)
    {
        parent::__construct([
                BoltRouteDefinition::class => ['autowire', LCRouteDefinition::class],//bolt
                DriverInterface::class => ['autowire', FileSystem::class],//stash
                Twig_LoaderInterface::class => [
                    'autowire',
                    Twig_Loader_Filesystem::class,
                    [
                        'paths' => [
                            __DIR__ . '/../../templates'
                        ],
                    ]
                ],//twig
                'localCache' => [
                    'alias',
                    CacheKeyValue::class,
                    [
                        function () {
                            /**
                             * @var Pool $pool
                             */
                            $pool = Factory::of($this)->produce(Pool::class);
                            $this->events->addListener(LCApp::EVENT_AFTER_LOGIC, [$pool, 'commit']);

                            return $pool;
                        },
                        86400
                    ]
                ],
                'logger' => ['alias', Logger::class],
                Logger::class => [
                    'autowire',
                    null,
                    [
                        'name' => 'default',
                        'handlers' => function () {
                            return array_map([$this->stubResolver, 'resolve'], $this->config('[log]', []));
                        },
                    ]
                ],
                'canvas' => ['alias', Canvas::class],
            ] + ($config ?: []));

        Configurator::config($this, $this->config('[container]', []));
    }

    public function config($key, $default = null)
    {
        return $this->access($this->config, $key, $default);
    }
}
