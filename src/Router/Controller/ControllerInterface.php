<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 12:48
 *
 */

namespace Turbine\Router\Controller;


use Interop\Container\ContainerInterface;
use League\Route\Http\RequestAwareInterface;
use League\Route\Http\ResponseAwareInterface;
use League\Route\Route;

interface ControllerInterface extends RequestAwareInterface, ResponseAwareInterface, ContainerInterface
{
    /**
     * @return Route
     */
    public function getRoute();

    /**
     * @param Route $route
     * @return $this
     */
    public function setRoute(Route $route);

    public function handle();

}