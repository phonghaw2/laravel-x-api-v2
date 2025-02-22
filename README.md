
#  A Laravel package for the Twitter REST API V2 endpoints.

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/phonghaw2/laravel-google-analytics.svg?style=flat-square)](https://packagist.org/packages/phonghaw2/laravel-google-analytics)

## Installation

> For Laravel ^6.0|^7.0|^8.0

This package can be installed through Composer.

``` bash
composer require phonghaw2/laravel-x-api-v2
```

Optionally, you can publish the config file of this package with this command:

``` bash
php artisan vendor:publish --provider="Phonghaw2\X\XServiceProvider"
```


## Config

The following config file will be published in `config/x.php`

```php
return [
    'api_base_uri' => 'https://api.twitter.com/2/',

    // The unique identifier for the Twitter account.
    // Account ID of the Twitter user
    'app_id' => env('X_APP_ID', 1),

    // Your Twitter API consumer key (public key), obtained from the Twitter Developer Portal.
    // This key is used to identify your app when making API requests. (Consumer API key)
    'api_key' => env('X_APP_KEY', 'CONSUMER_KEY'),

    // Your Twitter API consumer secret, also obtained from the Twitter Developer Portal.
    'api_key_secret' => env('X_APP_KEY_SECRET', 'CONSUMER_SECRET'),

    // The bearer token, used for OAuth 2.0 authentication.
    // It is used in the HTTP headers when making requests to Twitter's API without the need for user authentication.
    'bearer_token' => env('X_BEARER_TOKEN', 'BEARER_TOKEN'),

    // Access token, used for user authentication and authorization for Twitter API access on behalf of the user.
    // You must request this token after getting user consent.
    'access_token' => env('X_ACCESS_TOKEN', 'ACCESS_TOKEN'),

    // Access token secret, paired with the access token to authenticate and authorize API calls.
    'access_token_secret' => env('X_ACCESS_TOKEN_SECRET', 'ACCESS_TOKEN_SECRET'),
];
```

## Usage
Here are a few examples of the provided methods:

### TweetLookup

```php
use Twitter;
// Post lookup by Post IDs
Twitter::tweetLookup()
->setQueryParams([
    'ids' => 111111111,
    'max_results' => 5, // Must be a number between 1 and 100 (default: 100)
    'pagination_token' => '5qym3iwo3naekslszn59lxy1d9nmc6q'
])
->lookupPost();

// Creation of a Post
Twitter::tweetLookup()
->setPostData([
    'media' => [
        "media_ids": [
            "11111"
        ],
        "tagged_user_ids": [
            "2222"
        ]
    ],
])
->create()
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
