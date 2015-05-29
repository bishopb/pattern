<?php
namespace BishopB\Pattern;

use BishopB\Pattern\AbstractTestCase;
use BishopB\Pattern\Literal;

class LiteralTest extends AbstractTestCase
{
    /**
     * @dataProvider provides_non_strings
     * @expectedException BishopB\Pattern\Exception\InvalidArgument
     */
    public function test_construction_requires_exactly_one_string($arg)
    {
        $this->make_sut('Literal', $arg);
    }
}
