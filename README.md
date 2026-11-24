<div align="center">

# PHPStan Exception Codes

</div>

This package provides [PHPStan](https://phpstan.org/) rules for exception codes, as TYPO3 expects them: every `throw` carries a unique, plausible code.

> [!IMPORTANT]
> This package is intended for use in my personal projects only. It is not designed for general use.

## 🔥 Installation

[![Packagist](https://img.shields.io/packagist/v/konradmichalik/phpstan-exception-codes?label=version&logo=packagist)](https://packagist.org/packages/konradmichalik/phpstan-exception-codes)
[![Packagist Downloads](https://img.shields.io/packagist/dt/konradmichalik/phpstan-exception-codes?color=brightgreen)](https://packagist.org/packages/konradmichalik/phpstan-exception-codes)

```bash
composer require konradmichalik/phpstan-exception-codes --dev
```

## ⚡ Usage

If you have the [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer)
package installed, there's nothing more to do. The [rules](rules.neon) are automatically included.

### Manual Setup

```yaml
# phpstan.neon

includes:
  - %rootDir%/../../konradmichalik/phpstan-exception-codes/rules.neon
```

## 🧰 Included Rules

| Identifier | Reported when |
|------------|---------------|
| `exceptionCode.missing` | `throw new X(...)` passes no code, or `0` |
| `exceptionCode.outOfRange` | The code is outside the configured range |
| `exceptionCode.duplicate` | The same code is thrown in more than one place, reported at every place |

Only `throw new X(...)` is checked, and only when the second constructor parameter of `X` is an `int $code`. Exceptions such as `new HttpProblemException(404, ...)` are skipped. A code that is not a constant (`$previous->getCode()`) is skipped as well.

Duplicates are collected across all analysed files, so they work with parallel analysis and the result cache. A code can be reported as out of range and as a duplicate at the same time.

### Range

The range is fixed, not derived from the current time, so a run gives the same result every day. The defaults match TYPO3 timestamp codes:

```yaml
parameters:
  exceptionCodes:
    minimum: 1000000000
    maximum: 2147483647
```

## 🛠️ Typical Overrides

Existing codes that cannot be changed yet belong in the baseline. Ignore a single throw per identifier and path, and keep the reason next to it:

```yaml
parameters:
  ignoreErrors:
    # Code is part of the public API and cannot change before the next major release
    -
      identifier: exceptionCode.duplicate
      path: Classes/Legacy/LegacyService.php
```

Widen the range if a project uses random ten-digit codes instead of timestamps:

```yaml
parameters:
  exceptionCodes:
    maximum: 9999999999
```

## 🧑‍💻 Development

`composer test` runs the PHPUnit rule tests in `tests/Rule`. The analysed fixtures live in `tests/Rule/data`.

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
