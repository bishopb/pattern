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
        $this->literals = array (
            'a',
            str_repeat('a', 10),
            str_repeat('a', 100000),
        );
        $this->subjects = array (
            'b',
            str_repeat('b', 10),
            str_repeat('b', 100000),
        );
    }

    /**
     * @iterations 1000
     */
    public function clock_strcmp_matches()
    {
        foreach ($this->literals as $a) {
            foreach ($this->literals as $b){
                strcmp($a, $b);
            }
        }
    }

    /**
     * @iterations 1000
     */
    public function clock_strcmp_non_matches()
    {
        foreach ($this->literals as $a) {
            foreach ($this->subjects as $b){
                strcmp($a, $b);
            }
        }
    }

    /**
     * @iterations 1000
     */
    public function clock_strcasecmp_matches()
    {
        foreach ($this->literals as $a) {
            foreach ($this->literals as $b){
                strcasecmp($a, $b);
            }
        }
    }

    /**
     * @iterations 1000
     */
    public function clock_strcasecmp_non_matches()
    {
        foreach ($this->literals as $a) {
            foreach ($this->subjects as $b){
                strcasecmp($a, $b);
            }
        }
    }
}
