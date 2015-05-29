<?php
namespace BishopB\Pattern;

use \PHPUnit_Framework_TestCase;
use \ReflectionClass;
use \StdClass;

/**
 * Base class for all test cases.
 */
class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    // -=-= Data Providers =-=-

    /**
     * Provide a bunch of non-strings.
     */
    public function provides_non_strings()
    {
        return array (
            array (null),
            array (false),
            array (true),
            array (1),
            array (1.0),
            array (array ()),
            array (new StdClass()),
        );
    }

    // PROTECTED API

    /**
     * Make a System Under Test (SUT) corresponding to the given class.
     * Pass that SUT the remaining arguments, if any.
     */
    protected function make_sut($relclass /* ... */)
    {
        $args  = array_slice(func_get_args(), 1);
        $class = new ReflectionClass(sprintf('\BishopB\Pattern\%s', $relclass));
        return $class->newInstanceArgs($args);
    }
}
