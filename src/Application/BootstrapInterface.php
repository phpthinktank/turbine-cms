<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 10.01.2016
* Time: 15:33
*/

namespace Turbine\Application;


use Interop\Container\ContainerInterface;

interface BootstrapInterface
{

    const ENVIRONMENT = 'default';
    const ENVIRONMENT_NAME = 'TURBINE_ENVIRONMENT';

    /**
     * @return string
     */
    public function getEnvironment();

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * @return string
     */
    public function getRootPath();

    /**
     * @return mixed
     */
    public function boot();

}