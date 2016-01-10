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
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MvcStrategy implements StrategyInterface
{

    /**
     * @param KernelInterface $kernel
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function dispatch(KernelInterface $kernel, RequestInterface $request, ResponseInterface $response)
    {
        // TODO: Implement dispatch() method.
    }
}