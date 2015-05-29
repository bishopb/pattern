<?php
/**
 * Concise and expressive string-matching library for PHP.
 * @author Bishop Bettini <bishop@php.net>
 */
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
