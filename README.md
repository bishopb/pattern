# Stencil
Stencil is a PHP library for applying patterns to subject strings using a
consistent, fluent API.  Stencil unifies the API for `strcmp` and family,
`fnmatch`, `preg_match`, and `version_compare`, while also providing
convenience methods for common string matching operations.

Stencil might be for you if:

* You're tired of referring to the PHP user manual for the argument order of
`strpos` and friends.
* You're frustrated that there's no simple, built-in implementation to find if
a string ends with another.
* You're tired of off-by-one errors when doing simple string checks.
* You want your code to read as you intend it to function.

| Branch | Unit Tests | Coverage |
| ------ | ---------- | -------- |
| [![Latest Stable Version](https://poser.pugx.org/bishopb/stencil/v/stable.png)](https://packagist.org/packages/bishopb/stencil) | [![Build Status](https://travis-ci.org/bishopb/stencil.png?branch=master)](https://travis-ci.org/bishopb/stencil) | [![Coverage Status](https://coveralls.io/repos/bishopb/stencil/badge.png?branch=master)](https://coveralls.io/r/bishopb/stencil?branch=master)|

## Quickstart

Install with [Composer][1]: `composer require bishopb/stencil:~0.1`

Use:

```php
use BishopB\Stencil;

// common matching API regardless of pattern language
$subjects = array ( 'Capable', 'Enabler', 'Able', );
$patterns = array (
    new Literal('Able'),
    new Wildcard('*able*'),
    new Pcre('^\w*Able\w*$')->fold(),
);
foreach ($subjects as $subject) {
    foreach ($patterns as $pattern) {
        $pattern->matches($subject) and "$pattern matches $subject";
    }
}

// literal string matching sugar
$able = new Literal('Able')->fold();
$able->foundIn('tablet');
$able->begins('Abletic');
$able->ends('Parable');
$able->sorts->before('active');
$able->sorts->after('aardvark');

// version string matching sugar
$stable = new Version('1.0.0');
$stable->matches('1.0.0');
$stable->before('1.0.1');
$stable->after('0.9.9');
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

* `$pattern` is a plain old string, which means you can make probable mistakes
like: `fnmatch('^foo.*bar', $input)`
* `strcmp` and family return an orderable result that doesn't encourage
intenional programming. Consider:
`if (strcmp('foo', $input)) { echo 'pop quiz: matches foo?'; }`
* Functions to perform literal comparisons are scattered all over the place:
`strcmp`, `strcasecmp`, `strpos`, `stripos`, etc.
* Can be difficult to remember which argument is pattern and which is subject
(compare `strpos` and `preg_match`).
* How one specifies "case-insensitive" various widely amongst the comparison
functions.
* If your code initially accepts literal matches, then you want to support
regular expressions, you have to re-write your code.
* Not every platform supports `fnmatch`.

This library provides a fast, thin abstraction over the built-in pattern
matching functions so that developers can:

* Express the intent of the code clearly
* Uniformly indicate case-sensitivity
* Rely on type hinting too ensure pattern and function parity
* Access `fnmatch()` behavior even on systems that don't have it


## Performance

This package's philosophy is simple: to deliver syntactic sugar with minimal
run-time fat.  Pattern object calls are a thin facade over the fastest
implementation of the given match.  Space is conserved as much as possible.

### Run-time benchmarks

Benchmark | Native PHP | This Library | % Diff
----------|------------|--------------|-------

### Peak-memory consumption benchmarks

Benchmark | Native PHP | This Library | % Diff
----------|------------|--------------|-------

*All benchmarks run 1000 times on a small, unloaded EC2 instance. Refer to
`tests/benchmarks` for actual code.*


## Advanced usage

### Manipulating the search subjects

Typically methods in the pattern classes (`Literal`, `Wildcard`, and `Pcre`)
take strings.  However, you can also pass instances of `Subject`, which is
a lightweight string class fit with methods common to string comparison:

```php
use BishopB\Stencil;

$device  = new Literal('Tablet')->fold();
$version = new Version('8.1');
$subject = new Subject('    Microsoft Tablet running Windows 8.1.0RC242.')-trim();

$device->matches(
    $subject->
    column(' ', 1) // explode at space and get the 1st index (0-based)
);
$version->after(
    $subject->
    column(' ', -1)-> // explode at space and get the last index (nth-from last)
    substring(0, 4)   // only the first 5 characters
);
```

### Faster searching of big text or with repeated searches

When your subject text is long, or you expect to compare your literal pattern to
many different subjects, it's worth it to "study" the literal pattern for
improved performance.

```php

// notice the use of study()
// without this, searching would be much slower
$zebra = new Literal('zebra')->fold()->study();
$words = file_get_contents('/usr/share/dict/words') or die('No dictionary');
$zebra->foundIn($words);
```

You may be wondering: how many characters is "long"?  Or, how many iterations
is "many"?  Well, I suppose it depends.  But, a long time ago, some PHP
internals [benchmarking][2] suggested a length of 5000+ or more would make
studying worth it.


## FAQ

### Why not just use the built-ins?

For the reasons mentioned above.  Personally, I wrote this library because
I kept referring to the official docs on the argument order for the built-ins
and because common use cases aren't handled concisely.

### Why not add more stringy methods, like `length()`, to `Subject`?

The package overall aims to support pattern matching in the lightest weight
possible.  Bulking up `Subject` with methods unrelated to pattern matches
conflicts with this goal.

[1]: http://getcomposer.org/
[2]: http://grokbase.com/t/php/php-internals/0869z2aemb/algorithm-optimizations-string-search#20080611g4vev3qwk7sj0sdwmgjtg7pjyc
