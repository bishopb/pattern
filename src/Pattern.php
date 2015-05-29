<?php
/**
 * @package bishopb/pattern
 * @author Bishop Bettini <bishop@php.net>
 */
namespace BishopB\Pattern;

use \BishopB\Pattern\Exception\InvalidArgument;

/**
 * Represents a generic pattern and acts as a base class for all patterns in
 * this library.
 * @abstract
 */
abstract class Pattern
{
    /**
     * Create a pattern.
     * @param string $pattern The pattern to use.
     * @throws \BishopB\Pattern\Exception\InvalidArgument if not given a string
     */
    public function __construct($pattern)
    {
        if (is_string($pattern)) {
        } else {
            throw new InvalidArgument(sprintf(
                'Pattern given as type %s, expecting string',
                gettype($pattern)
            ));
        }
    }
}
