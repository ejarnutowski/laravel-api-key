Laravel API Key Auth
========

[![Build](https://github.com/cable8mm/laravel-api-key/actions/workflows/build.yml/badge.svg)](https://github.com/cable8mm/laravel-api-key/actions/workflows/build.yml)
[![PHP Linting (Pint)](https://github.com/cable8mm/laravel-api-key/actions/workflows/coding-style-php.yml/badge.svg)](https://github.com/cable8mm/laravel-api-key/actions/workflows/coding-style-php.yml)
[![Latest Stable Version](http://poser.pugx.org/cable8mm/laravel-api-key/v)](https://packagist.org/packages/cable8mm/laravel-api-key)
[![Total Downloads](http://poser.pugx.org/cable8mm/laravel-api-key/downloads)](https://packagist.org/packages/cable8mm/laravel-api-key)
[![Latest Unstable Version](http://poser.pugx.org/cable8mm/laravel-api-key/v/unstable)](https://packagist.org/packages/cable8mm/laravel-api-key)
[![License](http://poser.pugx.org/cable8mm/laravel-api-key/license)](https://packagist.org/packages/cable8mm/laravel-api-key)
[![PHP Version Require](http://poser.pugx.org/cable8mm/laravel-api-key/require/php)](https://packagist.org/packages/cable8mm/laravel-api-key)

This repository was transferred to https://github.com/cable8mm/auth-by-key then it was archived.

본 레포지토리는 https://github.com/cable8mm/auth-by-key 로 이동되었고, 아카이브 되었습니다.

This version is a [fork](https://github.com/ejarnutowski/laravel-api-key).

## Installation

Run `composer require cable8mm/laravel-api-key`.

Run the migrations

    $ php artisan migrate

1 new database tables will be created:

* api_keys

## Managing Keys

Generate a new key using `php artisan apikey:generate {name}`.  The name argument is the name of your API key.  All new keys are active by default.

```bash
$ php artisan apikey:generate app1
  
// API key created
// Name: app1
// Key: 0ZdNlr7LrQocaqz74k6usQsOsqhqSIaUarSTf8mxnHuQVh9CvKAfpUy94VvBmFMq
```

Deactivate a key using `php artisan apikey:deactivate {name}`.

```bash
$ php artisan apikey:deactivate app1
  
// Deactivated key: app1
```

Activate a key using `php artisan apikey:activate {name}`.

```bash
$ php artisan apikey:activate app1
  
// Activated key: app1
```
    
Delete a key.  You'll be asked to confirm.  Keys are [soft-deleted](https://laravel.com/docs/eloquent#soft-deleting) for record keeping.

```bash
$ php artisan apikey:delete app1
  
// Are you sure you want to delete API key 'app1'? (yes/no) [no]:
// > yes
  
// Deleted key: app1
```

List all keys.  The -D or --deleted flag includes deleted keys
    
```bash
$ php artisan apikey:list -D
 
// +----------+----+-------------+---------------------+------------------------------------------------------------------+
// | Name     | ID | Status      | Status Date         | Key                                                              |
// +----------+----+-------------+---------------------+------------------------------------------------------------------+
// | app1     | 5  | deleted     | 2017-11-03 13:54:51 | 0ZdNlr7LrQocaqz74k6usQsOsqhqSIaUarSTf8mxnHuQVh9CvKAfpUy94VvBmFMq |
// | app2     | 1  | deleted     | 2017-11-02 22:34:28 | KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW |
// | app3     | 3  | deactivated | 2017-11-02 23:12:34 | IrDlc7rSCvUzpZpW8jfhWaH235vJAqFwyzVWpoD0SLGzOimA6hcwqMvy4Nz6Hntn |
// | app4     | 2  | active      | 2017-11-02 22:48:13 | KZEl4Y2HMuL013xvg6Teaa7zHPJhGy1TDhr2zWzlQCqTxqTzyPTcOV6fIQZVTIU3 |
// +----------+----+-------------+---------------------+------------------------------------------------------------------+
```

## Usage

### Implementing Authorization

A new `auth.apikey` route middleware has been registered for you to use in your routes or controllers.  Below are examples on how to use middleware, but for detailed information, check out [Middleware](https://laravel.com/docs/middleware) in the Laravel Docs.

Route example

```php
Route::get('api/user/1', function () {
    //
})->middleware('auth.apikey');

```

Controller example

```php
class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.apikey');
    }
}
```

### Authorizing Requests

In order to pass the `auth.apikey` middleware, requests must include an `X-Authorization` header as part of the request, with its value being an active API key.

    X-Authorization: KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW

## Unauthorized Requests

Requests that do not pass authorization will receive an HTTP 401 Status Code with the following response

```json
{
    "errors": [
        {
            "message": "Unauthorized"
        }
    ]
}
```

## Laravel Nova

Publish the nova resource file

```sh
php artisan vendor:publish --tag=laravel-api-key-nova
```

If you will typically need to overwrite,

```sh
php artisan vendor:publish --tag=laravel-api-key-nova --force
```

## Fix coding style

```sh
composer lint
```

## Test

```sh
composer test
```

## License

The Laravel API Key package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
