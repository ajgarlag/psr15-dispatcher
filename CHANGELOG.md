# [CHANGELOG](http://keepachangelog.com/)
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [0.4.3] - 2023-08-24

### Added
- Add support for psr/http-message:^2

## [0.4.2] - 2023-08-24

### Removed
- Drop support of PHP <8.0

## [0.4.1] - 2022-04-28

### Fixed
- Move `phpstan` requirements to dev

## [0.4.0] - 2022-04-28

### Changed
- `Pipe` and `Stack` classes are `final`.

### Removed
- Drop support of PHP <7.4

## [0.3.0] - 2018-10-22

### Changed
- Updated to PSR-15

## [0.2.0] - 2017-09-20

### Changed
- Substitution of `Delegate` related code with `RequestHandler` to be compatible with
  http-interop/http-middleware:0.5

## [0.1.1] - 2017-01-17

### Added
- `Pipe` and `Stack` can be initialized with an array of middlewares.

## 0.1.0 - 2017-01-13

### Added
- `Pipe` class.
- `Stack` class.

[Unreleased]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.4.3...master
[0.4.3]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.4.2...0.4.3
[0.4.2]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.4.1...0.4.2
[0.4.1]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.4.0...0.4.1
[0.4.0]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/ajgarlag/psr15-dispatcher/compare/0.1.0...0.1.1
