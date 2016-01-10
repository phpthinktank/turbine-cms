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


use Application\HttpBootstrap;
use Blast\Config\Factory;
use Blast\Config\Locator;
use Turbine\Resources;

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
     * @var $bootstrap
     */
    private $environment;

    /**
     * AbstractInitiator constructor.
     * @param string $nodeFile
     * @param string $environment
     * @param Resources $resource
     * @throws EnvironmentNotFoundException
     */
    public function __construct($nodeFile, $environment, Resources $resource)
    {

        $this->setFactory($resource->getFactory());
        $this->setLocator($resource->getLocator());
        $this->setEnvironment($environment);

        //set environment to load config

        $nodes = $this->getFactory()->load($nodeFile, $this->getLocator());

        if (!isset($nodes[ $environment ])) {
            throw new EnvironmentNotFoundException($environment);
        }

        $this->setNodes($this->sortNodes($nodes[ $environment ]));

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
     * @return mixed
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param mixed $environment
     * @return AbstractInitiator
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    protected function sortNodes($nodes)
    {
        $lowest = 0;
        $highest = 0;

        //get lowest and highest integer
        foreach ($nodes as $node) {
            if (!isset($node['priority'])) {
                continue;
            }
            $priority = $node['priority'];
            if (!is_numeric($priority)) {
                continue;
            }

            $priority = intval($priority);

            if ($priority > $highest) {
                $highest = $priority;
            }

            if ($priority < $lowest) {
                $lowest = $priority;
            }
        }

        //get average integer for invalid priority
        $average = ($lowest + $highest) / 2;
        $highest++;
        $lowest--;

        //determine priority from node
        $getPriority = function ($node) use ($average, $lowest, $highest) {
            if (!isset($node['priority'])) {
                return $average;
            }

            $priority = $node['priority'];

            switch ($priority) {
                case "first":
                    $priority = $lowest;
                    break;
                case "last":
                    $priority = $highest;
                    break;
            }

            if (!is_numeric($priority)) {
                return $average;
            }

            return intval($priority);
        };

        //sort nodes
        uasort($nodes, function ($a, $b) use ($getPriority, $average, $lowest, $highest) {

            $priorityA = $getPriority($a);
            $priorityB = $getPriority($b);

            return ($priorityA > $priorityB) ? 1 : -1;
        });

        return $nodes;
    }

    abstract function create();

}