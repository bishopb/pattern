<?php
namespace BishopB\Pattern;

use BishopB\Pattern\AbstractTestCase;
use BishopB\Pattern\Pattern;
use Mockery as m;

class PatternTest extends AbstractTestCase
{
    /**
     * @dataProvider provides_non_strings
     * @expectedException BishopB\Pattern\Exception\InvalidArgument
     */
    public function test_construction_requires_exactly_one_string(/* ... */)
    {
        m::mock('BishopB\Pattern\Pattern', func_get_args())->makePartial();
    }

    public function test_fold_and_unfold_semantics()
    {
        $sut = m::mock('BishopB\Pattern\Pattern', array ('foo'))->makePartial();
        $this->assertSame($sut, $sut->fold());
        $this->assertSame($sut, $sut->unfold());
    }
}
