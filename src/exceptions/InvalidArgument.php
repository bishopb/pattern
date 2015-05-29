<?php
/**
 * Concise and expressive string-matching library for PHP.
 * @author Bishop Bettini <bishop@php.net>
 */
namespace BishopB\Pattern\Exception;

use \InvalidArgumentException;
use \BishopB\Pattern\Exception\PatternLibraryException;

/**
 * Used when a method is given an actual parameter violating the formal
 * parameter definition.  For example, when a pattern is constructed with
 * an array.
 */
class InvalidArgument extends InvalidArgumentException implements PatternLibraryException
{
}
