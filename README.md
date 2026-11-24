# PHPStan exception codes

PHPStan rules for exception codes, as TYPO3 expects them: every `throw` carries a unique code.

```bash
composer require konradmichalik/phpstan-exception-codes --dev
```

With [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer) the rules are active right away. Otherwise include `vendor/konradmichalik/phpstan-exception-codes/rules.neon`.

## Rules

| Identifier | Reported when |
|------------|---------------|
| `exceptionCode.missing` | `throw new X(...)` passes no code, or `0` |
| `exceptionCode.outOfRange` | The code is outside the configured range |
| `exceptionCode.duplicate` | The same code is thrown in more than one place, reported at every place |

Only `throw new X(...)` is checked, and only when the second constructor parameter of `X` is an `int`. Exceptions such as `new HttpProblemException(404, ...)` are skipped. A code that is not a constant (`$previous->getCode()`) is skipped as well.

The range is fixed, not derived from the current time, so a run gives the same result every day. The defaults match TYPO3 timestamp codes:

```neon
parameters:
	exceptionCodes:
		minimum: 1000000000
		maximum: 2147483647
```

## Development

```bash
composer test   # PHPUnit
composer lint   # composer normalize (dry run) and editorconfig check
composer fix
```

## License

GPL-3.0-or-later
