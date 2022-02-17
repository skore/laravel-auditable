# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.2.1] - 2022-02-17

### Fixed

- Fix `dropAuditables` with foreign key indexes

## [2.2.0] - 2022-02-11

### Added

- Support for Laravel 9

## [2.1.0] - 2021-12-07

### Added

- `replicating` event that fills createdBy / author (same way as creating does)
- Tests to cover the little bit this package offers

### Removed

- Support for Laravel 7

## [2.0.1] - 2021-01-20

### Added

- Support for PHP 8

## [2.0.0] - 2020-11-30

### Added

- Support for Laravel 8

### Removed

- Support for Laravel 5

## [1.0.1] - 2020-03-12

### Fixed

- Little typo (missing `$event` variable)

## [1.0.0] - 2020-03-11

### Added

- Initial release of the package
