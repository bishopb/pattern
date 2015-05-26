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

There are few problems with these API:

* `$pattern` is a plain old string, which means you can write probably wrong
code like: `fnmatch('^foo.*bar', $input)`
* Not every platform supports `fnmatch`
* `strcmp` and family return an orderable result that doesn't encorage intenional
programming. Consider: `if (strcmp('foo', $input)) { echo 'match?'; }`
* If you start by accepting literal matches, then want to support regular
expressions you have to re-write your code.

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
);
foreach ($patterns as $pattern) {
    if ($pattern->matches($input)) {
    }
}

// usability with native functions:
if (preg_match(new Pcre('^\s*+'), $input)) {
}

// typed interface to avoid giving wrong pattern to wrong function:
Fnmatch::match(new Wildcard('foo*bar'), $input);
Pcre::grep(new Pcre('^\s*#'), $lines);

// intentful and clarified code
$input   = 'aardvark';
$pattern = new Literal('aloof');
if ($pattern->sorts->before($input)) {
} else if ($pattern->sorts->after($input)) {
} else if ($pattern->sorts->same($input)) {
}

try {
    $pattern = new Pcre('^\s*');
    if ($pattern->sorts->before('foo')) {
    }
} catch (PcreException $ex) {
    echo 'Pcre doesnt support ordering relationship';
}
```
