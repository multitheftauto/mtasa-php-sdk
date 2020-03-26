# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed
- Use array unpackaging instead of using `call_user_func_array` in ResourceCall class

## [1.0.3] - 2020-03-14
### Fixed
- Fix JSON extension compatibility with PHP 7.4

## [1.0.2] - 2019-10-07
### Fixed
- Remove wrong scalar type declaration at resource call method (Thanks to [@MegaThorx](https://github.com/MegaThorx) in [PR #6](https://github.com/multitheftauto/mtasa-php-sdk/pull/6))

## [1.0.1] - 2019-10-03
### Added
- Add custom exceptions for server code errors

### Fixed
- Return null if data from server is empty when calling to a server function

## [1.0.0] - 2019-09-05
### Added
- Full rework of the SDK
- Add server model for IP and port
- Add authentication model for user and password
- Add possibility of choosing the http client, request and stream factory
- Add `call` property in `Resource` class to call functions

### Changed
- Uppercase first character of mta class
- Change parameter types of Mta class

## [0.4.0] - 2009-10-06
### Changed
- Renamed `public function mta(..)` to `public function __construct(..)`.

## [0.3.0] - 2008-04-17
### Added
- Neater syntax, support functions for callRemote (fix version makes call work with args)

## [0.2.0] - 2007-07-10
### Added
- Add authentication support

## [0.1.0] - 2007-06-26
### Added
- Initial release

[Unreleased]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v1.0.3...HEAD
[1.0.3]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v0.4.0...v1.0.0
[0.4.0]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/multitheftauto/mtasa-php-sdk/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/multitheftauto/mtasa-php-sdk/releases/tag/v0.1.0
