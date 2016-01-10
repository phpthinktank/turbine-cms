<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 10.01.2016
* Time: 12:29
*/

namespace Turbine\Config;


use Exception;

class UnknownNodeTypeException extends Exception
{

    public function __construct($message, Exception $previous = null)
    {
        parent::__construct(sprintf('Unknown node type "%s"!', $message), 1002, $previous);
    }
}