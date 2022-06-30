# Changelog

All notable changes to `nova-enum-field` will be documented in this file.

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
