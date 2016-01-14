<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 13.01.2016
 * Time: 11:49
 *
 */

namespace Turbine\System\Exception;

class InvalidClassException extends RuntimeException
{
    /**
     * @var string
     */
    private $actualClassname;

    /**
     * @var string
     */
    private $expectedClassname;

    /**
     * InvalidClassException constructor.
     * @param string|object $actual
     * @param string|object $expected
     */
    public function __construct($actual, $expected)
    {

        if (!is_object($actual)) {
            $actual = get_class($actual);
        }

        $this->actualClassname = $actual;

        if (!is_object($expected)) {
            $expected = get_class($expected);
        }

        $this->expectedClassname = $expected;

        parent::__construct(sprintf('Invalid class %s! Instance of %s expected!', $actual, $expected), 502);
    }

    /**
     * @return string
     */
    public function getActualClassname()
    {
        return $this->actualClassname;
    }

    /**
     * @return string
     */
    public function getExpectedClassname()
    {
        return $this->expectedClassname;
    }


}