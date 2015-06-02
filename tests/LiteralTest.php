<?php
namespace BishopB\Pattern;

use BishopB\Pattern\AbstractTestCase;
use BishopB\Pattern\Literal;

class LiteralTest extends AbstractTestCase
{
    /**
     * @dataProvider provides_string_pattern_and_matches_result
     */
    public function test_matches($pattern, $subject, $unfoldExpected, $foldExpected)
    {
        $this->assertSame(
            $unfoldExpected,
            $this->make_sut('Literal', $pattern)->matches($subject),
            "Pattern '$pattern' doesn't match subject '$subject'"
        );

        $this->assertSame(
            $unfoldExpected,
            $this->make_sut('Literal', $pattern)->fold()->matches($subject),
            "Folded pattern '$pattern' doesn't match subject '$subject'"
        );
    }

    public function provides_string_pattern_and_matches_result()
    {
        return array (
            array ('', '', true, true),
        );
    }
}
