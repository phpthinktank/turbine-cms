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
use Turbine\Application\Event\ConfigureEvent;
use Turbine\Application\Events\BootConfigEvent;
use Turbine\Resources;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Turbine\Application\Http\Foundation;
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
     * @return Bootstrap
     */
    protected function initHttp()
    {
        $factory = new DiactorosFactory();
        $this
            ->setRequest($this->getContainer()->has(ServerRequestInterface::class) ? $this->getContainer()->get(ServerRequestInterface::class) : $factory->createRequest(Request::createFromGlobals()))
            ->setResponse($this->getContainer()->has(ResponseInterface::class) ? $this->getContainer()->get(ResponseInterface::class) : $factory->createResponse(new Response()));

        return $this;
    }

    /**
     * @return Bootstrap
     */
    protected function initConfig()
    {
        $event = new BootConfigEvent();
        $event->setNodeFile('/config/nodes.json');
        $this->getEmitter()->emit($event);

        $initiator = new Initiator($event->getNodeFile(), $this->getEnvironment(), $this->getResources());
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

    protected function initServices()
    {
        $container = $this->getContainer();
        $config = $this->getConfig();

        $this->addService(BootstrapInterface::class, $this);
        $this->addService(Resources::class, $this->getResources());
        $this->addService(LoggerInterface::class, $this->getLogger());

        if (isset($config['providers'])) {
            $providers = $config['providers'];

            if (is_array($providers)) {
                foreach ($providers as $provider) {
                    $container->addServiceProvider($provider);
                }
            }
        }

        if (isset($config['services'])) {
            $services = $config['services'];

            if (is_array($services)) {
                foreach ($services as $alias => $service) {
                    $container->add($alias, $service);
                }
            }
        }

        return $this;

    }

    protected function configure()
    {
        $config = $this->getConfig();
        $event = new ConfigureEvent();
        $event->setConfig($config);
        $this->getEmitter()->emit($event);

        $this->setConfig($event->getConfig());

        return $this;
    }

    public function boot()
    {
        parent::boot();

        $this
            ->initHttp()
            ->initConfig()
            ->initServices()
            ->configure();

        return new Factory($this);
    }

    /**
     * Convenient boot loader
     * @param $rootPath
     * @param Foundation|string $application
     */
    public static function create($rootPath, $application = null)
    {
        $rootPath = realpath($rootPath);
        $loader = require_once $rootPath . '/vendor/autoload.php';
        $bootstrap = new Bootstrap($rootPath);
        $factory = $bootstrap
            ->setRootPath($rootPath)
            ->setResources(new Resources($rootPath . '/res'))
            ->setLoader($loader)
            ->boot();
        $factory->createApplication(
            $application instanceof KernelInterface || $bootstrap->getContainer()->has($application) ?
                $bootstrap->getContainer()->get($application) :
                new Foundation())
            ->dispatch($bootstrap->getRequest(), $bootstrap->getResponse());
    }

}