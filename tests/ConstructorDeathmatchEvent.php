<?php
namespace BishopB\Pattern;

use Athletic\AthleticEvent;
use BishopB\Pattern\EventFixtures as F;
use BishopB\Pattern\Literal;

/**
 * new Foo vs unserialize vs. (optionally) ReflectionClass::newInstanceWithoutConstructor
 * 
 * I suspect that new Foo is going to be the fastest.
 */
class ConstructorDeathmatchEvent extends AthleticEvent
{
    /**
     * @iterations 100
     */
    public function clock_constructor()
    {
        new Literal(F::tiny());
    }

    /**
     * @iterations 100
     */
    public function clock_unserialize()
    {
        unserialize('O:7:"Literal":0:{}')->pattern = F::tiny();
    }
}
