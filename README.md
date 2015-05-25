# Unified Pattern Matching

## Quickstart

Install via composer: `composer require bishopb/reo 0.1`

Use in your code:

```
use BishopB\Regex as Regex;

$re = new Regex('^\s*#', 'i');
$re->matches($input) or die('No match');
```

## Motivation
In PHP, you can match strings in several ways:

  # Literally, where you might do `0 === strcasecmp($pattern, $input)`
  # Using shell-style wildcards, like `true === fnmatch($pattern, $input)`
  # Using PCRE regular expressions, like `true === preg_match($pattern, $input)`
