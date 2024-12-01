# Changelog

All notable changes to `laravel-js-store` will be documented in this file

## 4.1.0 - 2024-03-25
- Add support for PHP 8.4
- Fixed some typo's
- Corrected the Copyright owner in the LICENSE file
- Update supported Laravel versions to exclude versions with a known CVE

## 4.0.0 - 2024-03-25
- Add support for Laravel 11
- Add support for PHP 8.3
- Drop support for Laravel 9
- Drop support for PHP 8.0

## 3.1.0 - 2024-01-08
- Render store data in the DOM using the @json blade directive

## 3.0.0 - 2023-03-01
- Add support for Laravel 10
- Add support for PHP 8.2
- Drop support for Laravel 7 and 8
- Drop support for PHP 7.4

## 2.1.0 - 2022-08-07
- Add support for Laravel 9

## 2.0.1 - 2021-07-01
- Fix PrepareStoreForNextOperation and update notes about Octane

## 2.0.0 - 2021-07-01
- Drop support for Laravel 6
- Drop support for PHP 7.2 and 7.3
- Add support for Laravel Octane

## 1.2.0 - 2021-02-02

- #2 Parse JSON client-side using `JSON.parse()` instead of outputting the object directly
- #3 Added support for Laravel 8
- #3 Dropped support for older Laravel en PHP versions (Laravel 5.8 and PHP 7.1)
- #4 Added View and Response macro - `view('index')->js('key', 'value')`

## 1.1.2 - 2019-10-25

- Fix: Start collecting DataProviders only when the script is rendered
- Fix: Updated namespacing

## 1.1.1 - 2019-10-25

- Fix: Loading correct config
- Fix: script view didn't render json correctly

## 1.1.0 - 2019-10-24

- Fix: View composer was bound to all views, it will now only be attached to `laravel-js-store::script`

## 1.0.0 - 2019-09-26

- initial release
