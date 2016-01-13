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
use Blast\Application\Strategy\StrategyInterface;
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
     * Create a new apllication from scratch
     * @param Application $application
     * @return Application
     */
    public function createApplication(Application $application)
    {
        $bootstrap = $this->getBootstrap();
        if($application->getContainer() === null){
            $application->setContainer($bootstrap->getContainer());
        }
        if($application->getConfig() === null){
            $application->setConfig($bootstrap->getConfig());
        }
        if($application->getStrategy() === null){
            $application->setStrategy($application->getContainer()->has(StrategyInterface::class) ? $application->getContainer()->get(StrategyInterface::class) : new MvcStrategy());
        }

        $bootstrap->addService(KernelInterface::class, $application);

        return $application;
    }

}