<?php namespace Litcanvas\Action\Pixel;

use Lit\Bolt\BoltContainer;
use Litcanvas\Bolt\LCAction;
use Litcanvas\Canvas;

class GetCanvasAction extends LCAction
{
    const PATH = '/api/canvas';

    /**
     * @var Canvas
     */
    protected $canvas;

    public function __construct(BoltContainer $container, Canvas $canvas)
    {
        parent::__construct($container);
        $this->canvas = $canvas;
    }


    protected function main()
    {
        return $this->json()->render([
            'canvas' => $this->canvas->getAllHex(),
        ]);
    }
}
