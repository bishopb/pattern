# Unified Pattern Matching for PHP
*Abstracts various pattern matching functionalities into a consistent, unified API.*

## Quickstart

Install:

```
composer require bishopb/upm 0.1`
```

Simple usage:

```
use BishopB\Upm;

$subject = new Subject('Fruit smoothies');
$subject->matches(new Wildcard('*moo*'));
$subject->matches(new Pcre('^\w{5} '));
$subject->fold()->beginsWith('FRUIT');
```

## Examples

### Literal string comparison

```
$haystack = new Subject('The quick brown fox jumps over the lazy dog.  ');
$haystack->contains('quick');  // true
$haystack->beginsWith('the');  // false (different case)
$haystack->endsWith('dog.');   // false (space at end)

$haystack->fold()->beginsWith('the'); // true (case is folded)
$haystack->trim()->endsWith('dog.');  // true (space trimmed)

$haystack->startAt(4)->beginsWith('quick'); // true (index 4 reads "quick")
$haystack->trim()->endAt(-5)->endsWith('lazy'); // true (remove whitespace & "dog.")
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


## Performance

Overhead must be negligable. Transliteral code must be within 1% of performance.
Lots of syntatic sugar, without run-time fat.
Put in tests.

## FAQ

### The Subject class acts a lot like a String class. Why not put more stringy methods in it?

Subject aims to facilitate the string searching and pattern matching, and the
package overall aims to be concise and performant.  To ensure all those aims
are met, only the bare minimum of functionality is in-built.
