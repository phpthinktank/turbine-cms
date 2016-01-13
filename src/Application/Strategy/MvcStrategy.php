<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 30.12.2015
* Time: 18:27
*/

namespace Turbine\Application\Strategy;


use Blast\Application\Kernel\KernelInterface;
use Blast\Application\Strategy\StrategyInterface;
use League\Route\RouteCollection;
use League\Route\RouteCollectionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Turbine\System\Exception\InvalidClassException;
use Turbine\System\Exception\RuntimeException;

class MvcStrategy implements StrategyInterface
{

    /**
     * @param KernelInterface $kernel
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Exception
     */
    public function dispatch(KernelInterface $kernel, RequestInterface $request, ResponseInterface $response)
    {

        $routerId = RouteCollectionInterface::class;
        $router = $kernel->getContainer()->get($routerId);

        if (!($router instanceof RouteCollectionInterface)) {
            throw new InvalidClassException($router, $routerId);
        }

        if (isset($config['routes'])) {
            foreach ($config['routes'] as $route) {
                $router->map($route['methods'], $route['path'], $route['handler']);
            }
        }

        if($request instanceof ServerRequestInterface && ($router instanceof RouteCollection || method_exists($router, 'dispatch'))){
            return $router->dispatch($request, $response);
        }

        throw new RuntimeException('Unable to dispatch router');

    }
}