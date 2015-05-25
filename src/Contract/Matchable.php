<?php
namespace BishopB\Upm\Contract;

/**
 * Declares that the object can match a simple string.
 */
interface Matchable
{
    public function matches(/* string */$string) /*: boolean */;
}
