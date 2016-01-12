<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 15:38
 *
 */

namespace Turbine\Application\Event;


use League\Event\AbstractEvent;

class ConfigureEvent extends AbstractEvent
{
    /**
     * @var array
     */
    private $config;

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getName()
    {
        return 'configure';
    }
}