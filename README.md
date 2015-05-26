# Unified Pattern Matching for PHP
*Abstracts various pattern matching functionalities into a consistent, unified API.*
Lots of syntatic sugar, without run-time fat.

## Quickstart

Install:

```
composer require bishopb/upm 0.1`
```

Use:

```
use BishopB\Upm;

$needle = new Literal('ark');
$needle->foundIn('aardvark'); // true

$pattern = new Pcre('^\s*#', 'i');
$pattern->matches('# a comment'); // true
```

## Motivation
In PHP, developers have four common ways to match strings to patterns:

Kind | Example Code | Example Pattern
:---:|------|----------------
Needle-in-Haystack | `0 === strcmp($pattern, $input)` | `'foobar'`
Versiony | `0 === version_compare($pattern, '7.0.0')` | `'7.0.0RC1'`
Shell-style wildcards | `true === fnmatch($pattern, $input)` | `'foo*bar?'`
PCRE regular expressions | `true === preg_match($pattern, $input)` | `'^\s*#'`

There are few problems with these API:

* `$pattern` is a plain old string, which means you can make probable mistakes like:
`fnmatch('^foo.*bar', $input)`
* `strcmp` and family return an orderable result that doesn't encourage intenional
programming. Consider: `if (strcmp('foo', $input)) { echo 'pop quiz: matches foo?'; }`
* Functions to perform literal comparisons are scattered all over the place: `strcmp`,
`strcasecmp`, `strpos`, `stripos`, etc.
* Can be difficult to remember which argument is pattern and which is subject (compare
`strpos` and `preg_match`.
* How one specifies "case-insensitive" various widely amongst the comparison functions.
* If your code initially accepts literal matches, then you want to support regular
expressions, you have to re-write your code.
* Not every platform supports `fnmatch`.

This library provides a fast, thin abstraction over the built-in pattern matching
functions to that developers can:

* Express the intent of the code clearly
* Uniformly indicate case-sensitivity
* Rely on type hinting too ensure pattern and function parity
* Access `fnmatch()` behavior even on systems that don't have it


```
use BishopB\Upm;

// consistent matching API, regardless of pattern language:
$patterns = array (
    new Needle('foobar'),
    new Version('1.0.1'),
    new Wildcard('foo*bar?'),
    new Pcre('^\s*#'),
);
foreach ($patterns as $pattern) {
    // regardless of type of pattern, there is a matches() method
    // that "does the right thing"
    if ($pattern->matches($input)) {
        // $input can be a plain-old string or a Subject object
    }
}

// downgrade nicely to native functdions
if (preg_match(new Pcre('^\s*+'), $input)) {
}

// typed passthru interface to avoid pattern-function disparity:
try {
    Pcre::grep(new Wildcard('foo*bar'), $lines);
} catch (PatternFunctionMismatch $ex) {
    echo $ex->getMessage(); // "Wildcard pattern passed to PCRE grep"
}

// consolidate needle-in-haystack matching functionality
// needle-centric:
$haystack = 'aardvark';
$needle   = new Literal('ark');

$needle->begins($haystack);              // false
$needle->ends($haystack);                // true
$needle->foundIn($haystack);             // true

// haystack-centric:
$haystack = new Subject('aardvark');
$needle   = 'ark';

$haystack->startsWith($needle); // false
$haystack->endsWith($needle);   // true
$haystack->contains($needle);   // true

$left  = 'apple';
$right = 'orange';
Literal::spaceship($left, $right); // emulate <=>

$left    = new Literal('apple');
$right_1 = 'orange';
$right_2 = new Literal('orange');
$left->sorts->before($right_1); // true;
$left->sorts->before($right_2); // true;
$left->sorts->after($right_1);  // false
$left->sorts->same($right_1);   // false

try {
    $pattern = new Pcre('^\s*');
    if ($pattern->sorts->before('foo')) {
    }
} catch (PcreException $ex) {
    echo 'PCRE doesnt support ordering relationship';
}
```

## Performance

Overhead must be negligable. Transliteral code must be within 1% of performance.
