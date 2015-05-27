# Unified Pattern Matching for PHP
*Abstract the various PHP pattern matching functionalities into a consistent, unified API.*


## Quickstart

Install:

```
composer require bishopb/upm 0.1`
```

Use:

```php
use BishopB\Upm;

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

Typically methods in the pattern classes (`Literal`, `Wildcard`, and `Pcre`)
take strings.  However, you can also pass instances of `Subject`, which is
a lightweight string class fit with methods common to string comparison:

```php
use BishopB\Upm;

$device  = new Literal('Tablet')->fold();
$version = new Version('8.1');
$subject = new Subject('    Microsoft Tablet running Windows 8.1.0RC242.')-trim();

$device->matches(
    $subject->
    part(' ', 1) // explode at space and get the 1st index
);
$version->after(
    $subject->
    part(' ', -1)-> // explode at space and get the last index
    substr(0, 6)    // only the first 6 characters
);
```


## FAQ

### Why not just use the built-ins?

For the reasons mentioned above.  Personally, I wrote this library because
I kept referring to the official docs on the argument order for the built-ins
and because common use cases aren't handled concisely.

### Why not add more stringy methods, like `length()`, to `Subject`?

The package overall aims to support pattern matching in the lightest weight
possible.  Bulking up `Subject` with methods unrelated to pattern matches
conflicts with this goal.
