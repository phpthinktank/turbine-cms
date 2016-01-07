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

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$psr7Factory = new DiactorosFactory();

(new Application())
->setContainer(new \League\Container\Container())
->setConfig([])
->setStrategy(new \Turbine\Application\MvcStrategy())
->dispatch(
    $psr7Factory->createRequest(Request::createFromGlobals()),
    $psr7Factory->createResponse(new Response())
);

