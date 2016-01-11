<?php
/**
 * Created by PhpStorm.
 * User: Marco Bunge
 * Date: 11.01.2016
 * Time: 07:42
 */

namespace Turbine\Application;


use Dotenv\Dotenv;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Turbine\Application\Http\Bootstrap;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

abstract class AbstractBootstrap implements BootstrapInterface
{

    /**
     * @var array
     */
    private $config;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var string
     */
    private $environment = self::ENVIRONMENT;

    public function __construct($rootPath, ContainerInterface $container)
    {
        $this->setup($rootPath)
            ->setContainer($container)
            ->initLogger()
            ->initErrorHandler()
            ->initEnvironment();
    }

    /**
     * @param $rootPath
     * @return AbstractBootstrap
     */
    protected function setup($rootPath)
    {
        error_reporting(E_ALL);
        umask(0);
        /* PHP version validation */
        if (version_compare(phpversion(), '5.5.0', '<') === TRUE) {
            echo 'Turbine CMS supports PHP 5.5.0 or later.';
            exit(1);
        }

        $this->setRootPath($rootPath);

        return $this;
    }

    /**
     * @return AbstractBootstrap
     */
    protected function initLogger()
    {
        if($this->getContainer()->has(LoggerInterface::class)){
            $this->getContainer()->get(LoggerInterface::class);
        }else{
            $this->setLogger(new Logger('system'));
        }


        return $this;
    }

    /**
     * @return Bootstrap
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
        $dotenv = new Dotenv($this->getRootPath());
        $dotenv->load();

        $environment = getenv('TURBINE_ENVIRONMENT');
        $this->setEnvironment(!$environment ? $this->getEnvironment() : $environment);

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return $this|AbstractBootstrap
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return $this|AbstractBootstrap
     */
    public function setContainer($container)
    {
        $this->container = $container;

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
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     * @return $this|AbstractBootstrap
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

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
     * @return $this|AbstractBootstrap
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

}