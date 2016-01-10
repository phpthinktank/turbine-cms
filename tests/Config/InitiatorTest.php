<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 09.01.2016
* Time: 16:26
*/

namespace Turbine\Tests\Config;


use Blast\Config\Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Turbine\Config\HttpInitiator as Initiator;

class InitiatorTest extends \PHPUnit_Framework_TestCase
{


    public function testHttpConfig()
    {
        $psr7Factory = new DiactorosFactory();
        $request = $psr7Factory->createRequest(Request::create('http://turbine.dev/test-node/?query-param=val'));
        $factory = new Factory();
        $locator = $factory->create(__DIR__ . '/../res/config');
        $initiator = new Initiator($factory, $locator);
        $initiator->init('/nodes.json');
        $initiator->setRequest($request);
        $data = $initiator->execute();
    }


}
