<?php
/**
 * Created by PhpStorm.
 * User: Marco Bunge
 * Date: 11.01.2016
 * Time: 07:42
 */

namespace Turbine\Application;


use Blast\Facades\FacadeFactory;
use Composer\Autoload\ClassLoader;
use Dotenv\Dotenv;
use Interop\Container\ContainerInterface;
use League\Container\Container;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Turbine\Application\Http\Bootstrap;
use Turbine\Container\ContainerAwareTrait as ContainerAwareTrait;
use Turbine\Container\StaticAwareTrait as StaticContainerAwareTrait;
use Turbine\Config\AwareTrait as ConfigAwareTrait;
use Turbine\Logger\AwareTrait as LoggerAwareTrait;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

abstract class AbstractBootstrap implements BootstrapInterface
{

    use ConfigAwareTrait;
    use ContainerAwareTrait, StaticContainerAwareTrait {
        StaticContainerAwareTrait::getContainer insteadof ContainerAwareTrait;
    }

    use LoggerAwareTrait {
        LoggerAwareTrait::getLogger as getPsrLogger;
    }

    /**
     * @var string
     */
    private $rootPath;

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
    private $environment = self::ENVIRONMENT;

    public function __construct($rootPath)
    {
        $this
            ->init()
            ->setRootPath($rootPath);;
    }

    public function boot()
    {
        $this
            ->initContainer()
            ->initLogger()
            ->initErrorHandler()
            ->initEnvironment()
            ->initEmitter();
    }

    /**
     * @return $this|AbstractBootstrap
     */
    protected function init()
    {
        /* PHP version validation */
        if (version_compare(phpversion(), '5.5.0', '<') === TRUE) {
            echo 'Turbine CMS supports PHP 5.5.0 or later.';
            exit(1);
        }

        return $this;
    }

    /**
     * @return $this|AbstractBootstrap
     */
    protected function initLogger()
    {
        $this->setLogger($this->getContainer()->has(LoggerInterface::class) ? $this->getContainer()->get(LoggerInterface::class) : new Logger('system'));

        return $this;
    }

    /**
     * @return $this|AbstractBootstrap
     */
    protected function initErrorHandler()
    {
        $whoops = new Run;

        if (ini_get('display_errors') === 1) {
            $whoops->pushHandler(new CallbackHandler(function () {
                $this->getLogger()->pushHandler(new StreamHandler($this->getRootPath() . '/res/logs/system.log'));
            }));
        } else {
            $whoops->pushHandler(new PrettyPageHandler);
        }

        $whoops->register();

        return $this;
    }

    /**
     * Determine environment from getenv. If no Environment is available set default environment
     * @return $this|AbstractBootstrap
     */
    protected function initEnvironment()
    {
        /**
         * initialize all environment vars and make publish them to php env (getenv, $_ENV)
         * @see https://github.com/vlucas/phpdotenv
         */
        $dotenv = new Dotenv($this->getRootPath());
        $dotenv->load();

        $environment = getenv(self::ENVIRONMENT_NAME);
        $this->setEnvironment(!$environment ? $this->getEnvironment() : $environment);

        return $this;
    }

    /**
     * Initialize container and facades. Container instance is used static by FacadeFactory::getContainer
     * @return $this
     */
    protected function initContainer()
    {
        if (!(FacadeFactory::getContainer() instanceof ContainerInterface)) {
            FacadeFactory::setContainer($this->getContainer() instanceof ContainerInterface ? $this->getContainer() : new Container());
        }

        return $this;
    }

    /**
     * Initialize container and facades. Container instance is used static by FacadeFactory::getContainer
     * @return $this
     */
    protected function initEmitter()
    {
        $this->setEmitter($this->getContainer()->has(EmitterInterface::class) ? $this->getContainer()->get(EmitterInterface::class) : new Emitter());

        return $this;
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
     * @return AbstractBootstrap
     */
    public function setEmitter($emitter)
    {
        $this->emitter = $emitter;
        return $this;
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
     * @return $this|AbstractBootstrap
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

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
     * @return LoggerInterface|Logger
     */
    public function getLogger()
    {
        return $this->getPsrLogger();
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
     * @return $this|AbstractBootstrap
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

}