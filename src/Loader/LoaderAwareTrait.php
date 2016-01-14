<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 15:05
 *
 */

namespace Turbine\Loader;


use Composer\Autoload\ClassLoader;

trait LoaderAwareTrait
{
    /**
     * @var ClassLoader
     */
    private $loader;

    /**
     * @return ClassLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * @param ClassLoader $loader
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;
    }

}