<?php namespace Litcanvas;

use Nimo\IErrorMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandler implements IErrorMiddleware
{
    public function __invoke(
        $error,
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        ob_start();
        echo '<xmp>', PHP_EOL;
        var_dump($error);
        $content = ob_get_clean();
        $response->getBody()->write($content);
        return $response;
    }
}
