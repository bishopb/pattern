<?php
namespace BishopB\Pattern;

use \BishopB\Pattern\Exception\InvalidArgument;

/**
 * Represents a literal pattern, one that has no meta-characters.
 */
class Literal
{
    /**
     * Create a Literal pattern.
     * @param string $pattern The literal pattern to use.
     * @throws \BishopB\Pattern\Exception\InvalidArgument if not given a string
     */
    public function __construct($pattern)
    {
        if (is_string($string)) {
        } else {
            throw new InvalidArgument(sprintf(
                'Pattern given as type %s, expecting string',
                gettype($pattern)
            ));
        }
    }
}
