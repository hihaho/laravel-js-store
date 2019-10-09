# Laravel JS Store

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hihaho/laravel-js-store.svg?style=flat-square)](https://packagist.org/packages/hihaho/laravel-js-store)
[![Build Status](https://img.shields.io/travis/hihaho/laravel-js-store/master.svg?style=flat-square)](https://travis-ci.org/hihaho/laravel-js-store)
[![Quality Score](https://img.shields.io/scrutinizer/g/hihaho/laravel-js-store.svg?style=flat-square)](https://scrutinizer-ci.com/g/hihaho/laravel-js-store)
[![Total Downloads](https://img.shields.io/packagist/dt/hihaho/laravel-js-store.svg?style=flat-square)](https://packagist.org/packages/hihaho/laravel-js-store)

Easily pass data to your view to create an initial state for your frontend.
This package let's your easily create and register global data providers (for each page), which for example is useful for user data.
You can also manually push data on the fly, for example in a controller.

## Installation

You can install the package via composer:

```bash
composer require hihaho/laravel-js-store:^1.0
```

Next you should render the js data on your page, there are a few different ways to do this:

#### Blade directive
Add the `@frontend_store` directive to your view:

``` html
<html>
    <head>
        
    </head>
    <body>
        @frontend_store
    </body>
</html>
```

#### Output the data yourself
You can also just output the data yourself, the way you want
``` html
<html>
    <head>
        
    </head>
    <body>
        <script>
            window.myData = @json(frontend_store());
        </script>
    </body>
</html>
```

#### Overwrite the default view
Create a new view: `resources/views/vendor/js-store/script.blade.php`.
Output the data the way you want and then include it using the blade directive (`@frontend_store`).

## Usage

There are two methods of pushing data to the store, through data-providers or pushing manually.

### Pushing manually

At any point in your application you can push data to the store using the helper, facade or through the laravel container.

You can push pretty much any type of data, as long as it can be cast to a string.

```php
// Using the helper
frontend_store()->put('user', Auth::user());

// Using the facade
\HiHaHo\LaravelJsStore\StoreFacade::put('user', Auth::user());

// Using the laravel container
app()->make(\HiHaHo\LaravelJsStore\Store::class)->put('user', Auth::user());
app()->make('js-store')->put('user', Auth::user());
```

### Data-providers

Data-providers are classes that can be used to globally define data that should be send to your frontend.
It's also a convenient way to store more logic related to the data, or define a rule if the data needs to be rendered.

The data-providers are defined in your config, so first you'll have to publish the config file.

```bash
php artisan vendor:publish --tag=laravel-js-store-config
```

This should create `config/js-store.php`.

Create a data provider using the artisan make command:
```bash
php artisan make:frontend-data-provider SomeName
```

This creates a data-provider which extends `HiHaHo\LaravelJsStore\AbstractFrontendDataProvider`.

An example of a data-provider might look like this:

```php
<?php

namespace App\Http\FrontendDataProviders;

use HiHaHo\LaravelJsStore\AbstractFrontendDataProvider;

class User extends AbstractFrontendDataProvider
{
    /**
     * The data that will be JSON encoded
     *
     * @return mixed
     */
    public function data()
    {
        return Auth::user();
    }
    
    public function hasData(): bool
    {
        // Only push the data when the user is signed in
        return Auth::check();
    }
}
```

Next, register you data-provider in `config/js-store.php`:

```
'data-providers' => [
    \App\Http\FrontendDataProviders\User::class,
],
```

Your data will now automatically be rendered in blade views (in this case only when the user is signed in).

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Robert Boes](https://github.com/RobertBoes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
