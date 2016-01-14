<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 13:22
 *
 */

namespace Turbine\Application\Events;


use League\Event\AbstractEvent;
use Turbine\Application\ControllerInterface;

class ControllerEvent extends AbstractEvent
{
    /**
     * @var string
     */
    private $action;

    /**
     * @var ControllerInterface|callable
     */
    private $controller;

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return ControllerInterface
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param ControllerInterface|callable $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function getName()
    {
        return 'router.dispatch.controller';
    }

}