<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 16:02
 *
 */

namespace Turbine\Application;


use Composer\Autoload\ClassLoader;
use League\Container\Container;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use Turbine\Container\ContainerAwareTrait;

class AbstractBootlet
{
    use ContainerAwareTrait;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @var ClassLoader
     */
    private $loader;

    /**
     * @var string
     */
    private $rootPath;

    public function __construct()
    {
        $this->setContainer(new Container());
        $this->setEmitter(new Emitter());
    }

    /**
     * @return EmitterInterface
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

    /**
     * @param EmitterInterface $emitter
     * @return $this
     */
    public function setEmitter($emitter)
    {
        $this->emitter = $emitter;
        return $this;
    }

    /**
     * @return ClassLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * @param ClassLoader $loader
     * @return $this
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @param string $rootPath
     * @return $this
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;
        return $this;
    }
}