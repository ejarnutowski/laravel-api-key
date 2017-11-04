Laravel API Key Auth
========

<a href="https://packagist.org/packages/ejarnutowski/laravel-api-key"><img src="https://poser.pugx.org/ejarnutowski/laravel-api-key/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/ejarnutowski/laravel-api-key"><img src="https://poser.pugx.org/ejarnutowski/laravel-api-key/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/ejarnutowski/laravel-api-key"><img src="https://poser.pugx.org/ejarnutowski/laravel-api-key/license.svg" alt="License"></a>

## Installation

Run `composer require ejarnutowski/laravel-api-key`.

In your `config/app.php` file, add the Laravel API Key service provider to the end of the `providers` array.

```php
'providers' => [
    ...
    Ejarnutowski\LaravelApiKey\Providers\ApiKeyServiceProvider::class,
],
```

Publish the migration files

    $ php artisan vendor:publish

Run the migrations

    $ php artisan migrate

3 new database tables will be created:

* api_keys
* api_key_access_events
* api_key_admin_events

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

## Event History

All API requests that pass authorization are logged in the `api_key_access_events` table.  A record is created for each request with the following information:

* api_key_id
* ip_address
* url
* created_at
* updated_at

Any time an API key is generated, activated, deactivated, or deleted, a record is logged in the `api_key_admin_events` table.  Each record contains the following information:

* api_key_id
* ip_address
* event
* created_at
* updated_at

## License

The Laravel API Key package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
