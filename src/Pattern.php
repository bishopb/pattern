<?php
/**
 * Concise and expressive string-matching library for PHP.
 * @author Bishop Bettini <bishop@php.net>
 */
namespace BishopB\Pattern;

use \BishopB\Pattern\Exception\InvalidArgument;

/**
 * Represents a generic pattern and acts as a base class for all patterns in
 * this library.
 *
 * @abstract
 */
abstract class Pattern
{
    /**
     * Create a pattern.
     *
     * @param string $pattern The pattern to use.
     * @throws \BishopB\Pattern\Exception\InvalidArgument if not given a string
     */
    public function __construct($pattern)
    {
        if (is_string($pattern)) {
            $this->pattern = $pattern;
        } else {
            throw new InvalidArgument(sprintf(
                'Pattern given as type %s, expecting string',
                gettype($pattern)
            ));
        }
    }

    /**
     * Set this pattern to case fold, IOW match case INsensitively.
     *
     * @return BishopB\Pattern\Pattern $this
     */
    public function fold()
    {
        $this->folded = true;
        return $this;
    }

    /**
     * Set this pattern to NOT case fold, IOW match case sensitively.
     * This is the default.
     *
     * @return BishopB\Pattern\Pattern $this
     */
    public function unfold()
    {
        $this->folded = false;
        return $this;
    }

    /**
     * Matches this pattern against a subject string, subject to folding and
     * any other options given to the pattern.
     *
     * @param string|BishopB\Pattern\Subject $subject To compare w/ this pattern
     * @return boolean
     */
    abstract public function matches($subject);

    // PROTECTED API

    /**
     * The pattern to match subjects against.
     *
     * @var string
     */
    protected $pattern = null;

    /**
     * Whether, or not, this pattern should be case folded.
     *
     * @var boolean
     */
    protected $folded = false;
}
