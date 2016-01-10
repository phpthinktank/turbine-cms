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

#ini_set('display_errors', 1);

$rooPath = realpath(__DIR__ . '/..');
$bootstrap = new \Application\HttpBootstrap(
    $rooPath,
    new \Turbine\Resources($rooPath . '/res'),
    new \League\Container\Container()
);
$bootstrap
    ->createApplication(new \Turbine\Application())
    ->setStrategy(new \Turbine\Application\Strategy\MvcStrategy())
    ->dispatch($bootstrap->getRequest(), $bootstrap->getResponse());


