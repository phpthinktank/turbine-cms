<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 15:57
 *
 */

namespace Turbine\Application\Http;

use Blast\Application\Kernel\KernelInterface;
use Turbine\Application\Http\Foundation as Application;
use Turbine\Application\Strategy\MvcStrategy;

class Factory
{
    /**
     * @var Bootstrap
     */
    private $bootstrap;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->setBootstrap($bootstrap);
    }

    /**
     * @return Bootstrap
     */
    public function getBootstrap()
    {
        return $this->bootstrap;
    }

    /**
     * @param Bootstrap $bootstrap
     */
    public function setBootstrap($bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * @param Application $application
     * @return Application
     */
    public function createApplication(Application $application)
    {
        $bootstrap = $this->getBootstrap();
        $application
            ->setContainer($bootstrap->getContainer())
            ->setConfig($bootstrap->getConfig())
            ->setStrategy(new MvcStrategy());

        $bootstrap->addService(KernelInterface::class, $application);

        return $application;
    }

}