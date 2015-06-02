<?php
namespace BishopB\Pattern;

use \BishopB\Pattern\Pattern;

/**
 * Represents a literal pattern, one that has no meta-characters.
 */
class Literal extends Pattern
{
    public function matches($subject)
    {
        if ($this->folded) {
            return (0 === strcasecmp($this->pattern, $subject));
        } else {
            return (0 === strcmp($this->pattern, $subject));
        }
    }
}
