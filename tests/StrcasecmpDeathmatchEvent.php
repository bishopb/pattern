<?php
namespace BishopB\Pattern;

use Athletic\AthleticEvent;
use BishopB\Pattern\EventFixtures as F;

/**
 * strcasecmp vs. strtolower+strcmp vs. preg_match/i
 * 
 * I suspect that strcasecmp will beat strtolower+strcmp, because
 * internally strcasecmp is implemented as a strtolower then a memcmp.
 * I don't know which will win between strcasecmp and preg_match, but
 * I suspect the preg_match engine will have some overhead that strcasecmp
 * will not, thus strcasecmp will edge out preg_match.
 */
class StrcasecmpDeathmatchEvent extends AthleticEvent
{
    /**
     * @iterations 100
     */
    public function clock_strcasecmp()
    {
        strcasecmp(F::medium('a'), F::medium('b'));
    }

    /**
     * @iterations 100
     */
    public function clock_preg_match()
    {
        preg_match(
            '/^' . preg_quote(F::medium('a'), '/') . '$/i',
            F::medium('b')
        );
    }

    /**
     * @iterations 100
     */
    public function clock_strtolower_and_strcmp()
    {
        $a = strtolower(F::medium('a'));
        $b = strtolower(F::medium('b'));
        strcmp($a, $b);
    }
}
