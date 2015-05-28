<?php
namespace BishopB\Stencil;

use Athletic\AthleticEvent;

/**
 * Clock string comparison.
 */
class StringComparisonEvent extends AthleticEvent
{
    public function classSetUp()
    {
        $this->tinyString1   = str_repeat('a', 2 << 4);
        $this->tinyString2   = str_repeat('b', 2 << 4);
        $this->smallString1  = str_repeat('a', 2 << 8);
        $this->smallString2  = str_repeat('b', 2 << 8);
        $this->mediumString1 = str_repeat('a', 2 << 16);
        $this->mediumString2 = str_repeat('b', 2 << 16);
        $this->largeString1  = str_repeat('a', 2 << 24);
        $this->largeString2  = str_repeat('b', 2 << 24);

        $this->deathmatch1_1 = str_repeat('a', 2 << 12);
        $this->deathmatch1_2 = str_repeat('a', 2 << 12);
    }

    //------------------------------------------------------------------------
    // strcmp
    // -----------------------------------------------------------------------

    /**
     * @iterations 1000
     */
    public function strcmp_single_char_match()
    {
        strcmp('a', 'a');
    }

    /**
     * @iterations 1000
     */
    public function strcmp_tiny_string_match()
    {
        strcmp($this->tinyString1, $this->tinyString1);
    }

    /**
     * @iterations 1000
     */
    public function strcmp_small_string_match()
    {
        strcmp($this->smallString1, $this->smallString1);
    }

    /**
     * @iterations 1000
     */
    public function strcmp_medium_string_match()
    {
        strcmp($this->mediumString1, $this->mediumString1);
    }

    /**
     * @iterations 1000
     */
    public function strcmp_large_string_match()
    {
        strcmp($this->largeString1, $this->largeString1);
    }

    /**
     * @iterations 1000
     */
    public function strcmp_tiny_to_large_mismatch()
    {
        strcmp($this->tinyString1, $this->largeString1);
    }

    //------------------------------------------------------------------------
    // strcasecmp
    // -----------------------------------------------------------------------

    /**
     * @iterations 1000
     */
    public function strcasecmp_single_char_match()
    {
        strcasecmp('a', 'a');
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_tiny_string_match()
    {
        strcasecmp($this->tinyString1, $this->tinyString1);
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_small_string_match()
    {
        strcasecmp($this->smallString1, $this->smallString1);
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_medium_string_match()
    {
        strcasecmp($this->mediumString1, $this->mediumString1);
    }

    /**
     * @iterations 1000
     */
    public function strcasecmp_large_string_match()
    {
        strcasecmp($this->largeString1, $this->largeString1);
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
        strcasecmp($this->deathmatch1_1, $this->deathmatch1_2);
    }

    /**
     * @iterations 100
     */
    public function deathmatch1_preg_match()
    {
        preg_match(
            '/^' . preg_quote($this->deathmatch1_1, '/') . '$/i',
            $this->deathmatch1_2
        );
    }

    /**
     * @iterations 100
     */
    public function deathmatch1_strtolower_and_strcmp()
    {
        $a = strtolower($this->deathmatch1_1);
        $b = strtolower($this->deathmatch1_2);
        strcmp($a, $b);
    }
}
