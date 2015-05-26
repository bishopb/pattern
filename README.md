# Unified Pattern Matching for PHP
*A simple library for easy-to-read*

## Quickstart

Install via composer: `composer require bishopb/upm 0.1`

Use in your code:

```
use BishopB\Upm;

$pattern = new Pcre('^\s*#', 'i');
$pattern->matches($input) or die('No match');
```

## Motivation
In PHP, developers have three ways to match a string against a pattern:

Kind | Code | Example Pattern
:---:|------|----------------
Literal | `0 === strcmp($pattern, $input)` | `'foobar'`
Shell-style wildcards | `true === fnmatch($pattern, $input)` | `'foo*bar?'`
PCRE regular expressions | `true === preg_match($pattern, $input)` | `'^\s*#'`
Versiony | `0 === version_compare($pattern, '7.0.0')` | `'7.0.0RC1'`

There are few problems with these API:

* `$pattern` is a plain old string, which means you can make probable mistakes like:
`fnmatch('^foo.*bar', $input)`
* `strcmp` and family return an orderable result that doesn't encourage intenional
programming. Consider: `if (strcmp('foo', $input)) { echo 'pop quiz: matches foo?'; }`
* Functions to perform literal comparisons are scattered all over the place: `strcmp`,
`strcasecmp`, `strpos`, `stripos`, etc.
* If you start by accepting literal matches, then want to support regular
expressions you have to re-write your code from `strcmp` to `preg_match`.
* Not every platform supports `fnmatch`

This library provides a thin abstraction over the built-in pattern matching
functions to that developers can:

* type hint patterns to avoid pattern and function disparity
* support new pattern languages without changing code
* access `fnmatch()` even on systems that don't have it


```
use BishopB\Upm;

// consistent matching API, regardless of pattern language:
$patterns = array (
    new Literal('foobar'),
    new Wildcard('foo*bar?'),
    new Pcre('^\s*#'),
    new Version('1.0.1'),
);
foreach ($patterns as $pattern) {
    if ($pattern->matches($input)) {
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

// consolidate literal matching functionality
/** 
 * FIXME: startsWith, endsWith
 * FIXME: contains
 * FIXME: orders before, after, same (stability)
 */
$haystack = 'aardvark';
$needle   = new Literal('ark');

$needle->begins($haystack);              // false
$needle->ends($haystack);                // true
$needle->foundIn($haystack);             // true

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
