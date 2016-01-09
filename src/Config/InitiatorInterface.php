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


use Blast\Config\Factory;
use Blast\Config\Locator;
use Psr\Http\Message\ServerRequestInterface;

interface InitiatorInterface
{

    const ENVIRONMENT = 'default';

    /**
     * AbstractInitiator constructor.
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator);

    /**
     * Get node data from node file
     *
     * @param $nodeFile
     * @return AbstractInitiator
     */
    public function init($nodeFile);

    /**
     * Load nodes
     * @return array
     */
    public function execute();

}