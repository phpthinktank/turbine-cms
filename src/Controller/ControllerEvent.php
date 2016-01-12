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

namespace Turbine\Controller;


use League\Event\AbstractEvent;

class ControllerEvent extends AbstractEvent
{
    /**
     * @var ControllerInterface
     */
    private $controller;

    /**
     * @return ControllerInterface
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param ControllerInterface $controller
     * @return ControllerEvent
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function getName()
    {
        return 'dispatch.controller';
    }

}