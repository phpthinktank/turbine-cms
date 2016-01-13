<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 10.01.2016
* Time: 16:12
*/

namespace Turbine\Core;


use Blast\Config\Factory;
use Blast\Config\Locator;

class Resources
{

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Locator
     */
    private $locator;

    public function __construct($resourceDir){

        $factory = new Factory();
        $locator = $factory->create($resourceDir);

        $this->setFactory($factory);
        $this->setLocator($locator);

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
     * @return Resources
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
     * @return Resources
     */
    protected function setLocator(Locator $locator)
    {
        $this->locator = $locator;

        return $this;
    }

}