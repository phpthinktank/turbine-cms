<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 10.01.2016
* Time: 15:24
*/

namespace Turbine\Application;

use Dotenv\Dotenv;
use League\Container\Container;
use Psr\Log\LoggerInterface;
use Turbine\Resources;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Turbine\Application;
use Turbine\Config\HttpInitiator;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class HttpBootstrap implements BootstrapInterface
{

    /**
     * @var string
     */
    private $environment = self::ENVIRONMENT;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var Resources
     */
    private $resources;


    /**
     * Bootstrap constructor.
     * @param $rootPath
     * @param Resources $resources
     * @param ContainerInterface $container
     */
    public function __construct($rootPath, Resources $resources, ContainerInterface $container)
    {
        $this
            ->setup($rootPath)
            ->setResources($resources)
            ->setContainer($container)
            ->initLogger()
            ->initErrorHandler()
            ->initEnvironment()
            ->initHttp()
            ->initConfig()
            ->initServices();
    }

    /**
     * @param $rootPath
     * @return HttpBootstrap
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
     * @return HttpBootstrap
     */
    protected function initLogger()
    {
        $this->setLogger(new Logger('system'));

        return $this;
    }

    /**
     * @return HttpBootstrap
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
     * @return HttpBootstrap
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
     * @return HttpBootstrap
     */
    protected function initHttp()
    {
        $psr7Factory = new DiactorosFactory();
        $this
            ->setRequest($psr7Factory->createRequest(Request::createFromGlobals()))
            ->setResponse($psr7Factory->createResponse(new Response()));

        return $this;
    }

    /**
     * @return HttpBootstrap
     */
    protected function initConfig()
    {
        $initiator = new HttpInitiator('/config/nodes.json', $this->getEnvironment(), $this->getResources());
        $initiator->setRequest($this->getRequest());

        $this->setConfig($initiator->create());

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
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return HttpBootstrap
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     * @return HttpBootstrap
     */
    public function setRequest($request)
    {
        $this->request = $request;

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
     * @return HttpBootstrap
     */
    public function setConfig($config)
    {
        $this->config = $config;

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
     * @return HttpBootstrap
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
     * @return HttpBootstrap
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

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
     * @return HttpBootstrap
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return Resources
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param Resources $resources
     * @return HttpBootstrap
     */
    public function setResources($resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * @param Application $application
     * @return Application
     */
    public function createApplication(Application $application)
    {
        $application = new Application();
        $application
            ->setContainer($this->getContainer())
            ->setConfig($this->getConfig())
            ->setStrategy(new Application\Strategy\MvcStrategy());

        return $application;
    }

    protected function initServices()
    {
        $container = $this->getContainer();
        $config = $this->getConfig();

        if ($container instanceof Container) {
            $container->add(BootstrapInterface::class, $this);
            $container->add(Resources::class, $this->getResources());
            $container->add(LoggerInterface::class, $this->getLogger());

            if (isset($config['services'])) {
                $services = $config['services'];

                if (is_array($services)) {
                    foreach ($services as $alias => $service) {
                        $container->add($alias, $service);
                    }
                }
            }

            if (isset($config['providers'])) {
                $providers = $config['providers'];

                if (is_array($providers)) {
                    foreach ($providers as $provider) {
                        $container->addServiceProvider($provider);
                    }
                }
            }

        } else {
            throw new \Exception('container ' . get_class($container) . ' is not supported!');
        }
    }

}