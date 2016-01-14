<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 13.01.2016
 * Time: 11:45
 *
 */

namespace Turbine\System\Exception;

class ClassNotFoundException extends RuntimeException
{
    /**
     * @var string
     */
    private $classname;

    /**
     * @param string|object $classname
     */
    public function __construct($classname)
    {
        if(!is_object($classname)){
            $classname = get_class($classname);
        }
        $this->classname = $classname;

        parent::__construct(sprintf('Unable to find %s', $classname), 501);
    }

    public function getClassname()
    {
        return $this->classname;
    }
}