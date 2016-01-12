<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 15:48
 *
 */

namespace Turbine\Application\Events;


class BootConfigEvent
{
    /**
     * @var array
     */
    private $nodeFile;

    /**
     * @return array
     */
    public function getNodeFile()
    {
        return $this->nodeFile;
    }

    /**
     * @param array $nodeFile
     */
    public function setNodeFile($nodeFile)
    {
        $this->nodeFile = $nodeFile;
    }

    public function getName()
    {
        return 'boot.config';
    }
}