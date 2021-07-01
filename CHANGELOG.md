# Changelog

All notable changes to `laravel-js-store` will be documented in this file

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
