<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 09.01.2016
* Time: 15:33
*/

namespace Turbine\Config;


use Blast\Config\Factory;
use Blast\Config\Locator;

abstract class AbstractInitiator implements InitiatorInterface
{

    /**
     * @var array
     */
    private $nodes = [];

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Locator
     */
    private $locator;

    /**
     * @var string
     */
    private $environment = self::ENVIRONMENT;

    /**
     * AbstractInitiator constructor.
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        $this->setFactory($factory);
        $this->setLocator($locator);
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param Factory $factory
     * @return AbstractInitiator
     */
    protected function setFactory(Factory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return Locator
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * @param Locator $locator
     * @return AbstractInitiator
     */
    protected function setLocator(Locator $locator)
    {
        $this->locator = $locator;

        return $this;
    }


    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param array $nodes
     * @return AbstractInitiator
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Get node data from node file
     *
     * @param $nodeFile
     * @return AbstractInitiator
     */
    public function init($nodeFile){
        $nodes = $this->getFactory()->load($nodeFile, $this->getLocator());
        $this->setNodes($nodes);

        return $this;
    }

    abstract function execute();

}