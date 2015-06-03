<?php
function clock($callback, $max = 100000) {
    xhprof_enable();

    for ($i = 0; $i < $max; $i++) {
        $callback();
    }

    return xhprof_disable();
}

function report($name, $data) {
    static $last = null;
    $frame  = $data['main()==>{closure}'];
    $metric = ($frame['wt']/$frame['ct'])/1000;
    printf("%-10s: %.4fs (%d)", $name, $metric, $frame['ct']);
    if (null !== $last) {
        printf(", %.1f%%", (($metric - $last)/$last)*100);
    }
    echo PHP_EOL;
    $last = $metric;
}


$a = str_repeat('a', 2<<8);
class O {
    public function strcmp($a) {
        strcmp($a, $a);
    }
}

report('strcmp', clock(function () use ($a) { strcmp($a, $a); }));
report('object', clock(function () use ($a) { $o = new O(); $o->strcmp($a); }));
