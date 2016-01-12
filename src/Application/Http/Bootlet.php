<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 16:01
 *
 */

namespace Turbine\Application\Http;

use Turbine\Application\AbstractBootlet;
use Turbine\Resources;

class Bootlet extends AbstractBootlet
{
    /**
     * @var Resources
     */
    private $resources;

    /**
     * @return Resources
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param Resources $resources
     * @return $this
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
        return $this;
    }

}