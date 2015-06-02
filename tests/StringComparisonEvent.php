<?php
namespace BishopB\Pattern;

use Athletic\AthleticEvent;
use BishopB\Pattern\EventFixtures as F;

/**
 * Clock string comparison.
 */
class StringComparisonEvent extends AthleticEvent
{
    //------------------------------------------------------------------------
    // strcmp
    // -----------------------------------------------------------------------

    /**
     * @iterations 1000
     */
    public function strcmp_single_char_match()
    {
        F::with(strcmp(F::single(), F::single()));
    }

    /**
     * @iterations 1000
     */
    public function strcmp_tiny_string_match()
    {
        F::with(strcmp(F::tiny('a'), F::tiny('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcmp_small_string_match()
    {
        F::with(strcmp(F::small('a'), F::small('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcmp_medium_string_match()
    {
        F::with(strcmp(F::medium('a'), F::medium('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcmp_large_string_match()
    {
        F::with(strcmp(F::large('a'), F::large('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcmp_tiny_to_large_mismatch()
    {
        F::with(strcmp(F::tiny('a'), F::large('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcmp_large_to_tiny_mismatch()
    {
        F::with(strcmp(F::large('a'), F::tiny('a')));
    }

    //------------------------------------------------------------------------
    // strcasecmp
    // -----------------------------------------------------------------------

    /**
     * @iterations 1000
     */
    public function strcasecmp_single_char_match()
    {
        F::with(strcasecmp('a', 'a'));
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_tiny_string_match()
    {
        F::with(strcasecmp(F::tiny('a'), F::tiny('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_small_string_match()
    {
        F::with(strcasecmp(F::small('a'), F::small('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_medium_string_match()
    {
        F::with(strcasecmp(F::medium('a'), F::medium('a')));
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_large_string_match()
    {
        F::with(strcasecmp(F::large('a'), F::large('a')));
    }

    //------------------------------------------------------------------------
    // deathmatch 1: strcasecmp vs. strtolower+strcmp vs. preg_match/i
    // -----------------------------------------------------------------------
    /**
     * I suspect that strcasecmp will beat strtolower+strcmp, because
     * internally strcasecmp is implemented as a strtolower then a memcmp.
     * I don't know which will win between strcasecmp and preg_match, but
     * I suspect the preg_match engine will have some overhead that strcasecmp
     * will not, thus strcasecmp will edge out preg_match.
     */

    /**
     * @iterations 100
     */
    public function deathmatch1_strcasecmp()
    {
        strcasecmp(F::medium('a'), F::medium('b'));
    }

    /**
     * @iterations 100
     */
    public function deathmatch1_preg_match()
    {
        preg_match(
            '/^' . preg_quote(F::medium('a'), '/') . '$/i',
            F::medium('b')
        );
    }

    /**
     * @iterations 100
     */
    public function deathmatch1_strtolower_and_strcmp()
    {
        $a = strtolower(F::medium('a'));
        $b = strtolower(F::medium('b'));
        strcmp($a, $b);
    }
}
