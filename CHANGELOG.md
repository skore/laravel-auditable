# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2025-03-11

### Added

- Laravel 12 support

### Removed

- Laravel 9 and 10 support

## [2.6.0] - 2024-05-24

### Added

- Laravel 11 support

## [2.5.2] - 2023-11-07

### Fixed

- SoftDeleting (deleting) model event isn't performing a save so we need to do it ourselves (done via package's event listener only when delete event)

## [2.5.1] - 2023-11-07

### Fixed

- `AuditableEvent::setUser($user, $action)` all parameters are now nullable

## [2.5.0] - 2023-11-07

### Added

- `AuditableEvent::setUser($user, $action)` to force user for some or all auditable events (actions line creating, updating or deleting)

## [2.4.0] - 2023-02-20

### Added

- Laravel 10 support

### Removed

- Laravel 6 support
- Laravel 8 support

## [2.3.2] - 2022-07-15

### Fixed

- Missing autoload-dev, tests were autoloaded with the released version (ouch!)

## [2.3.1] - 2022-05-31

### Fixed

- Minor fix disable methods

## [2.3.0] - 2022-05-31

### Fixed

- Event listeners being registered more than once

### Added

- Convenience methods to ignore auditable events: `withoutAuditable`, `disableAuditable` and `enableAuditable`

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
