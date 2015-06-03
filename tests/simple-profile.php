<?php

// run the callback a lot, return average run time in ms
function clock(\Closure $callback, $iterations = 10000) {
    $begin = microtime(true);

    for ($i = 0; $i < $iterations; $i++) {
        $callback();
    }

    $end = microtime(true) - $begin;

    return ($end / $iterations)*1000;
}

// nicely show me how long the name ran, and percent increase/decrease over last
function report($name, $time) {
    static $last = null;
    printf("%-12s: %.8fms", $name, $time);
    if (null !== $last) {
        printf(", %.1f%%", (($time - $last)/$last)*100);
    }
    echo PHP_EOL;
    $last = $time;
}


// fixtures
$a = str_repeat('a', 2<<8);

class O {
    public function strcmp($a) {
        strcmp($a, $a);
    }
}

// do it
report('direct call', clock(function () use ($a) { strcmp($a, $a); }));
report('object call', clock(function () use ($a) { $o = new O(); $o->strcmp($a); }));
