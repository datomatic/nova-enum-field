# Changelog

All notable changes to `nova-enum-field` will be documented in this file.

## v1.9.0 - 2024-04-03

- Fixed a bug with Pure Enum (thanks to @korobkovandrey)
- Add Laravel 11 compatibility

## v1.8.0 - 2023-10-07

- fix error with `StringBackedEnum` with numeric string

## v1.7.0 - 2023-08-16

- Display the value returned by the method defined in "property" by @AndreSchwarzer

## v1.6.1 - 2023-02-15

Laravel 10 support

## v1.6.0 - 2022-09-28

- Allow closure in the `attach` method.
- Fixed correct use of property parameter on resource edit select

## v1.5 - 2022-07-25

- nullable field support
- json sub-array field (json_column->enum)

## v1.4.0 - 2022-07-02

- updating for datomatic/enum-helper compatibility

## v1.3.2 - 2022-06-30

- fix for dynamic methods

## v1.3.1a - 2022-06-28

- refactor default filter method

## v1.3.0 - 2022-06-28

- moved name and default params of filters to callable

```php
// from
EnumBooleanFilter::make('Stato', 'status', CourseStatus::class, CourseStatus::DEFAULT)

// to
EnumBooleanFilter::make('status', CourseStatus::class)->name('Stato')->default(CourseStatus::DEFAULT)










```
## v1.2.1 - 2022-06-21

- fixed errors with base pure enum

## v1.2.0 - 2022-06-20

- added compatibility with datomatic/laravel-enum-helper
- added custom property option
- added subset of cases option

## v1.1.0 - 2022-06-04

- Added compatibility with [datomatic/enum-helper](https://github.com/datomatic/enum-helper)

## v1.0.0 - 2022-06-07

First release 🚀
