<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 13:09
 *
 */

namespace Turbine\Container;


use Interop\Container\ContainerInterface;

interface AwareTraitInterface
{
    /**
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer($container);
}