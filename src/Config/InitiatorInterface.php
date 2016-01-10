<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 09.01.2016
* Time: 16:03
*/

namespace Turbine\Config;


use Application\HttpBootstrap;
use Blast\Config\Factory;
use Blast\Config\Locator;
use Turbine\Resources;

interface InitiatorInterface
{

    /**
     * AbstractInitiator constructor.
     * @param string $nodeFile
     * @param string $environment
     * @param Resources $resource
     * @throws EnvironmentNotFoundException
     */
    public function __construct($nodeFile, $environment, Resources $resource);

    /**
     * Get node data from node file
     *
     * @return array
     */
    public function create();

}