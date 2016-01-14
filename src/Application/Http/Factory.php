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
use Turbine\Application\AbstractFactory;
use Turbine\Application\Http\Foundation;
use Turbine\Application\Strategy\MvcStrategy;

class Factory extends AbstractFactory
{

    /**
     * Create a new apllication from scratch
     * @param KernelInterface $application
     * @return KernelInterface
     */
    public function createApplication(KernelInterface $application)
    {
        $bootstrap = $this->getBootstrap();
        if ($application->getContainer() === null) {
            $application->setContainer($bootstrap->getContainer());
        }

        if ($application->getConfig() === null) {
            $application->setConfig($bootstrap->getConfig());
        }

        if ($application->getStrategy() === null) {
            $application->setStrategy($application->getContainer()->has(StrategyInterface::class) ? $application->getContainer()->get(StrategyInterface::class) : new MvcStrategy());
        }

        if ($bootstrap instanceof Bootstrap) {
            $bootstrap->addService(KernelInterface::class, $application);
        }

        return $application;
    }

}