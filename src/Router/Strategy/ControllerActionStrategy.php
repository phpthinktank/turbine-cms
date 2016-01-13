<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 11:46
 *
 */

namespace Turbine\Router\Strategy;


use League\Event\Emitter;
use League\Event\EmitterInterface;
use League\Route\Route;
use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use Turbine\Application\Events\ControllerEvent;
use Turbine\Application\ControllerInterface;
use Turbine\Application\Events\ResponseEvent;
use Turbine\System\Exception\RuntimeException;

class ControllerActionStrategy extends AbstractStrategy implements StrategyInterface
{

    /**
     * @return EmitterInterface
     */
    public function getEventEmitter()
    {
        return $this->getContainer()->has(EmitterInterface::class) ? $this->getContainer()->get(EmitterInterface::class) : new Emitter();
    }

    /**
     * Dispatch the controller, the return value of this method will bubble out and be
     * returned by \League\Route\Dispatcher::dispatch, it does not require a response, however,
     * beware that there is no output buffering by default in the router
     *
     * Emit dispatch.controller event
     *
     * This method is passed an optional third argument of the route object itself.
     *
     * @param callable $controller
     * @param array $vars - named wildcard segments of the matched route
     * @param \League\Route\Route|null $route
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws RuntimeException
     */
    public function dispatch(callable $controller, array $vars, Route $route = null)
    {
        $action = null;

        if (is_string($controller) && strpos($controller, '::') !== false) {
            list($controller, $action) = explode('::', $controller);
        }

        $controller = $this->getContainer()->get($controller);

        if ($controller instanceof ControllerInterface) {
            $controller
                ->setRequest($this->getRequest())
                ->setResponse($this->getResponse())
                ->setRoute($route);
        }

        $controllerEvent = $this->onDispatchController($controller, $action);

        if (!method_exists($this->getContainer(), 'call')) {
            throw new RuntimeException(
                sprintf(
                    'To use the parameter strategy, the container must implement the (::call) method. (%s) does not.',
                    get_class($this->getContainer())
                )
            );
        }

        $response = $this->getContainer()->call([$controllerEvent->getController(), $controllerEvent->getAction()], $vars);

        //emit router.dispatch.response event
        $responseEvent = $this->onDispatchResponse($controller, $response, $action);


        return $this->determineResponse($responseEvent->getResponse());

    }

    /**
     * @param callable $controller
     * @param $action
     * @return ControllerEvent
     */
    protected function onDispatchController(callable $controller, $action)
    {
        $controllerEvent = new ControllerEvent;
        $this->getEventEmitter()->emit(
            $controllerEvent
                ->setController($controller)
                ->setAction($action)
        );
        return $controllerEvent;
    }

    /**
     * @param callable $controller
     * @param $response
     * @param $action
     * @return ResponseEvent
     */
    protected function onDispatchResponse(callable $controller, $response, $action)
    {
        $responseEvent = new ResponseEvent();
        $this->getEventEmitter()->emit(
            $responseEvent
                ->setResponse($response),
            $controller, $action
        );
        return $responseEvent;
    }
}