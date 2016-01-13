<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 13.01.2016
 * Time: 15:52
 *
 */

namespace Turbine\Application;


use Blast\Application\Kernel\KernelInterface;

abstract class AbstractFactory
{
    /**
     * @var BootstrapInterface
     */
    private $bootstrap;

    public function __construct(BootstrapInterface $bootstrap)
    {
        $this->setBootstrap($bootstrap);
    }

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

    /**
     * Create a new apllication from scratch
     * @param KernelInterface $application
     * @return KernelInterface
     */
    abstract public function createApplication(KernelInterface $application);
}