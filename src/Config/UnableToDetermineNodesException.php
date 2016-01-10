<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 10.01.2016
* Time: 12:37
*/

namespace Turbine\Config;


use Exception;

class UnableToDetermineNodesException extends Exception
{

    public function __construct(Exception $previous = null)
    {
        parent::__construct('Unable to determine node!', 1003, $previous);
    }
}