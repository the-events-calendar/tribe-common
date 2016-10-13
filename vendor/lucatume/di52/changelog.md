# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [1.3.2] - 2016-04-19
### Fixed
- nested dependency resolving issue

## [1.3.1] - 2016-04-19
### Added
- more informative exception message when default primitive value is missing

## [1.3.0] - 2016-04-19
### Added
- support for the custom bindings
- support for same class singleton binding

## Changed
- performance optimization

## [1.2.6] - 2016-04-11
### Changed
- internal workings to improve performance (using [@TomBZombie benchmarks](https://github.com/TomBZombie/php-dependency-injection-benchmarks)

## [1.2.5] - 2016-03-06
### Added
- support for decorator pattern in PHP 5.2 compatible syntax
- code highlighting for code examples in doc (thanks @omarreiss)

## [1.2.4] - 2016-03-05
### Added
- tests for uncovered code

## [1.2.3] - 2016-03-04
### Fixed
- singleton resolution for same implementations

## [1.2.2] - 2016-02-13
- doc updates

## [1.2.1] - 2016-02-13
### Added
- `hasTag($tag)` method to the container
- `isBound($classOrInterface)` method to the container
- support for deferred service providers

## [1.2.1] - 2016-01-23
### Added
- tagging support
- service providers support 

## [1.2.0] - 2016-01-22
### Added
- the binding and automatic resolution API ([code inspiration](https://www.ltconsulting.co.uk/automatic-dependency-injection-with-phps-reflection-api/))

## [1.1.2] - 2016-01-19
### Fixed
- resolution for objects in arrays

## [1.1.1] - 2016-01-19
### Added
- support for the `%varName%` variable notation.

## [1.1.0] - 2016-01-18
### Added
- array resolution support for the Array Access API.
- the changelog.

[Unreleased]: https://github.com/lucatume/di52/compare/1.3.2...HEAD
[1.3.2]: https://github.com/lucatume/di52/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/lucatume/di52/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/lucatume/di52/compare/1.2.6...1.3.0
[1.2.6]: https://github.com/lucatume/di52/compare/1.2.5...1.2.6
[1.2.5]: https://github.com/lucatume/di52/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/lucatume/di52/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/lucatume/di52/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/lucatume/di52/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/lucatume/di52/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/lucatume/di52/compare/1.1.2...1.2.0
[1.2.0]: https://github.com/lucatume/di52/compare/1.1.2...1.2.0
[1.1.2]: https://github.com/lucatume/di52/compare/1.0.3...1.1.2
[1.1.1]: https://github.com/lucatume/di52/compare/1.0.3...1.1.2
[1.1.0]: https://github.com/lucatume/di52/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/lucatume/di52/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/lucatume/di52/compare/1.0.1...1.0.2
