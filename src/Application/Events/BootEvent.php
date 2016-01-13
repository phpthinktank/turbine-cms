<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 13.01.2016
 * Time: 15:48
 *
 */

namespace Turbine\Application\Event;


use League\Event\AbstractEvent;
use Turbine\Application\BootstrapInterface;

class BootEvent extends AbstractEvent
{
    /**
     * @var BootstrapInterface
     */
    private $bootstrap;

    /**
     * @return BootstrapInterface
     */
    public function getBootstrap()
    {
        return $this->bootstrap;
    }

    /**
     * @param BootstrapInterface $bootstrap
     */
    public function setBootstrap($bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    public function getName()
    {
        return 'application.boot';
    }
}