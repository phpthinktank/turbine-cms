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

namespace Turbine\Application\Http;

use Blast\Application\Kernel\KernelInterface;
use League\Container\Container;
use Psr\Log\LoggerInterface;
use Turbine\Application\AbstractBootstrap;
use Turbine\Application\BootstrapInterface;
use Turbine\Application\Strategy\MvcStrategy;
use Turbine\Resources;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Turbine\Application\Http\Foundation as Application;
use Turbine\Config\Http\Initiator;

class Bootstrap extends AbstractBootstrap implements BootstrapInterface
{

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var Resources
     */
    private $resources;


    /**
     * Bootstrap constructor.
     * @param $rootPath
     * @param Container $container
     * @param Resources $resources
     */
    public function __construct($rootPath, Container $container, Resources $resources)
    {
        parent::__construct($rootPath, $container);

        $this
            ->setResources($resources)
            ->initHttp()
            ->initConfig()
            ->initServices();
    }

    /**
     * @return Bootstrap
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
     * @return Bootstrap
     */
    protected function initConfig()
    {
        $initiator = new Initiator('/config/nodes.json', $this->getEnvironment(), $this->getResources());
        $initiator->setRequest($this->getRequest());

        $this->setConfig($initiator->create());

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return parent::getContainer();
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
     * @return Bootstrap
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
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
     * @return Bootstrap
     */
    public function setResponse($response)
    {
        $this->response = $response;

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
     * @return Bootstrap
     */
    public function setResources($resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * League Container implementation
     *
     * @param $alias
     * @param null $concrete
     * @param bool $share
     * @return $this|\League\Container\Definition\DefinitionInterface|mixed
     */
    public function addService($alias, $concrete = null, $share = false)
    {
        return $this->getContainer()->add($alias, $concrete, $share);
    }

    /**
     * @param Application $application
     * @return Application
     */
    public function createApplication(Application $application)
    {
        $application
            ->setContainer($this->getContainer())
            ->setConfig($this->getConfig())
            ->setStrategy(new MvcStrategy());

        $this->addService(KernelInterface::class, $application);

        return $application;
    }

    protected function initServices()
    {
        $container = $this->getContainer();
        $config = $this->getConfig();

        $this->addService(BootstrapInterface::class, $this);
        $this->addService(Resources::class, $this->getResources());
        $this->addService(LoggerInterface::class, $this->getLogger());

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

    }

}