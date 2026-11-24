# Changelog

## 0.1.0

Initial release.

- `exceptionCode.missing`: `throw new X(...)` passes no code, or `0`
- `exceptionCode.outOfRange`: the code is outside `exceptionCodes.minimum` to `exceptionCodes.maximum` (defaults `1000000000` to `2147483647`)
- `exceptionCode.duplicate`: the same code is thrown in more than one place
