<?php
namespace BishopB\Pattern\Exception;

use BishopB\Pattern\AbstractTestCase;
use BishopB\Pattern\Exception\InvalidArgument;

class InvalidArgumentTest extends AbstractTestCase
{
    public function test_hierarchy()
    {
        $sut = new InvalidArgument();
        $this->assertInstanceOf(
            '\BishopB\Pattern\Exception\PatternLibraryException',
            $sut
        );
        $this->assertInstanceOf(
            '\InvalidArgumentException',
            $sut
        );
    }
}
