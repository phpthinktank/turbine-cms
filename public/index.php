<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 30.12.2015
* Time: 14:23
*/

use Blast\Application\Kernel\Foundation as Application;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//register error handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

//create config
$config = function(\Psr\Http\Message\ServerRequestInterface $serverRequest){
    $factory = new \Blast\Config\Factory();
    $locator = $factory->create(__DIR__ . '/res/config');
    $initiator = new \Turbine\Config\HttpInitiator($factory, $locator);
    $initiator->init('nodes.json');
    $initiator->setRequest($serverRequest);
    return $initiator->execute();
};


//register http handler
$psr7Factory = new DiactorosFactory();
$request = $psr7Factory->createRequest(Request::createFromGlobals());

(new Application())
->setContainer(new \League\Container\Container())
->setConfig($config($request))
->setStrategy(new \Turbine\Application\MvcStrategy())
->dispatch(
    $request,
    $psr7Factory->createResponse(new Response())
);

