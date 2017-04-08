<?php namespace Litcanvas\Twig;

use Lit\Core\Interfaces\IView;
use Lit\Core\Traits\ViewTrait;

class TwigView implements IView
{
    use ViewTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $data;

    public function __construct(\Twig_Environment $twig, string $name, array $data = [])
    {
        $this->twig = $twig;
        $this->name = $name;
        $this->data = $data;
    }


    public function render(array $data = [])
    {
        $this->getEmptyBody()->write($this->twig->render($this->name, $data + $this->data));

        return $this->response
            ->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
