<?php
namespace BishopB\Pattern;

use Athletic\AthleticEvent;
use BishopB\Pattern\EventFixtures as F;

/**
 * Clock the Literal implementation.
 */
class LiteralEvent extends AthleticEvent
{
    /**
     * @iterations 1000
     */
    public function literal_single_char_match()
    {
        F::with(new Literal('a'))->matches('a');
    }

    /**
     * @iterations 1000
     */
    public function literal_tiny_string_match()
    {
        F::with(new Literal(F::tiny()))->matches(F::tiny());
    }
}
