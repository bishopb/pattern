# Pattern
Pattern is a string-matching PHP library sporting a consistent, fluent API.
Pattern unifies the API for `strcmp` and family, `fnmatch`, `preg_match`, and
`version_compare`, while also offering convenience methods for common string-
matching operations.

Pattern might be for you if:

* You're tired of referring to the PHP user manual for the argument order of
`strpos` and friends.
* You're frustrated that there's no simple, built-in implementation to find if
a string ends with another.
* You're tired of off-by-one errors when doing simple string checks.
* You want your code to read as you intend it to function.

| Branch | Unit Tests | Coverage |
| ------ | ---------- | -------- |
| [![Latest Stable Version](https://poser.pugx.org/bishopb/pattern/v/stable.png)](https://packagist.org/packages/bishopb/pattern) | [![Build Status](https://travis-ci.org/bishopb/pattern.png?branch=master)](https://travis-ci.org/bishopb/pattern) | [![Coverage Status](https://coveralls.io/repos/bishopb/pattern/badge.png?branch=master)](https://coveralls.io/r/bishopb/pattern?branch=master)|

## Quickstart

Install with [Composer][1]: `composer require bishopb/pattern:~0.1`

Use:

```php
use BishopB\Pattern;

// common matching API regardless of pattern language
$subjects = array ( 'Capable', 'Enabler', 'Able', );
$patterns = array (
    new Pattern\Literal('Able'),
    new Pattern\Wildcard('*able*'),
    new Pattern\Pcre('^\w*Able\w*$')->fold(),
);
foreach ($subjects as $subject) {
    foreach ($patterns as $pattern) {
        $pattern->matches($subject) and "$pattern matches $subject";
    }
}

// literal matching sugar
$able = new Pattern\Literal('Able')->fold();
$able->foundIn('tablet');
$able->begins('Abletic');
$able->ends('Parable');
$able->sorts->before('active');
$able->sorts->after('aardvark');

// version matching sugar
$stable = new Pattern\Version('1.0.0');
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
`if (! strcasecmp('foo', $input)) { echo 'pop quiz: matches foo?'; }`
* Functions to perform literal comparisons are scattered all over the place:
`strcmp`, `strcasecmp`, `strpos`, `stripos`, etc.
* Both `strcasecmp` and `==` are [dangerous][2] ways to compare strings.
* Can be difficult to remember which argument is pattern and which is subject
(compare `strpos` and `preg_match`).
* How one specifies "case-insensitive" various widely amongst the comparison
functions.
* If your code initially accepts literal matches, then you want to support
regular expressions, you have to re-write your code.
* Not every platform supports `fnmatch`.

This library provides a fast, thin abstraction over the built-in pattern
matching functions to mitigate these problems.


## Performance

This package's philosophy is simple: to deliver syntactic sugar with minimal
run-time fat.  API calls are a thin facade over the fastest implementation of
the requested match.  Space is conserved as much as possible.

### Run-time benchmarks

Meaurements for different tests in operations per second.

Benchmark | Native PHP | This Library | % Diff
----------|-----------:|-------------:|------:
strcmp_single_char_match | | |
strcmp_tiny_string_match | 3,798.21683 | |
strcmp_small_string_match | | |
strcmp_medium_string_match | 32,991.49158 | |
strcmp_large_string_match | 71.65056 | |
strcmp_tiny_to_large_mismatch | 54,822.84512 | |
strcasecmp_single_char_match | 90,357.87224 | |
strcasecmp_tiny_string_match | 75,753.89895 | |
strcasecmp_small_string_match | 30,056.59029 | |
strcasecmp_medium_string_match | 436.80003 | |
strcasecmp_large_string_match | 1.55844 | |

### Peak-memory consumption benchmarks

Benchmark | Native PHP | This Library | % Diff
----------|-----------:|-------------:|------:

*Note*: All benchmarks run a minimum of 1,000 times on a small, unloaded EC2 instance
using PHP 5.3.  Refer to `tests/*Event.php` for actual code.  Refer to the
[Travis CI][3] builds for run times on different PHP versions.


## Advanced usage

### Manipulating the search subjects

Typically methods in the pattern classes (`Literal`, `Wildcard`, and `Pcre`)
take strings.  However, you can also pass instances of `Subject`, which is
a lightweight string class fit with methods common to string comparison:

```php
use BishopB\Pattern;

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
internals [benchmarking][4] suggested a length of 5000+ or more would make
studying worth it.


## FAQ

### Why not just use the built-ins?

For the reasons mentioned above.  Personally, I wrote this library because
I kept referring to the official docs on the argument order for the built-ins
and because common use cases aren't handled concisely.  In summary, this
library lets me write less code and be more clear in meaning.

For example, I see a lot of code following this pattern:
```php
if (! strcmp($actual, $expected)) {
    $this->doSomething();
} else {
    throw new \RuntimeException('Actual does not match expected');
}
```

It's technically right.  But, to me, it looks wrong.  I find this much easier
to read:

```php
if ($actual->matches($expected)) {
    $this->doSomething();
} else {
    throw new \RuntimeException('Actual does not match expected');
}
```

There is a related side benefit.  In weak-mode PHP, functions that receive
an invalid parameter emit a warning and return `null`.  Since `null` evaluates
falsey, the example above runs `doSomething` unexpectedly.  Consider:

```php
// ?password=[]
if (! strcmp($_GET['password'], $user->password)) {
    $this->login($user);
} else {
    throw new \RuntimeException('Invalid password');
}
```

Why? Because `true === (! (null === strcmp(array (), '******')))`. In this
library, an exception is raised if you try to match against an array.


### Why not add more stringy methods, like `length()`, to `Subject`?

The package overall aims to support pattern matching in the lightest weight
possible.  Bulking up `Subject` with methods unrelated to pattern matches
conflicts with this goal.

[1]: http://getcomposer.org/
[2]: https://bugs.php.net/bug.php?id=64069
[3]: https://travis-ci.org/bishopb/pattern
[4]: http://grokbase.com/t/php/php-internals/0869z2aemb/algorithm-optimizations-string-search#20080611g4vev3qwk7sj0sdwmgjtg7pjyc
