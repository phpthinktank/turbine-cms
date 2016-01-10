<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 10.01.2016
* Time: 12:06
*/

namespace Turbine\Config;


use Exception;

class EnvironmentNotFoundException extends \Exception
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct(sprintf('Environment "%s" not found on system!', $message), 1001, $previous);
    }

}