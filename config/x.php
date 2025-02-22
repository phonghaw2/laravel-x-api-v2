<?php

return [
    'api_base_uri' => 'https://api.x.com/2/',

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

    /*
    |--------------------------------------------------------------------------
    | Fields parameter
    |--------------------------------------------------------------------------
    |
    | Here you may configure the valid values to be used as parameters in the query.
    */

    // This fields parameter enables you to select which specific Tweet fields will deliver in each returned Tweet object.
    // https://developer.x.com/en/docs/x-api/data-dictionary/object-model/tweet
    'tweet.fields' => [
        'attachments',
        'author_id',
        'context_annotations',
        'conversation_id',
        'created_at',
        'edit_controls',
        'entities',
        'geo',
        'id',
        'in_reply_to_user_id',
        'lang',
        'non_public_metrics',
        'public_metrics',
        'organic_metrics',
        'promoted_metrics',
        'possibly_sensitive',
        'referenced_tweets',
        'reply_settings',
        'source',
        'text',
        'withheld',
    ],

    // This fields parameter enables you to select which specific media fields will deliver in each returned Tweet.
    // https://developer.x.com/en/docs/x-api/data-dictionary/object-model/media
    'media.fields' => [
        'duration_ms',
        'height',
        'media_key',
        'preview_image_url',
        'type',
        'url',
        'width',
        'public_metrics',
        'non_public_metrics',
        'organic_metrics',
        'promoted_metrics',
        'alt_text',
        'variants',
    ],

    // This fields parameter enables you to select which specific place fields will deliver in each returned Tweet.
    // https://developer.x.com/en/docs/x-api/data-dictionary/object-model/place
    'place.fields' => [
        'contained_within',
        'country',
        'country_code',
        'full_name',
        'geo',
        'id',
        'name',
        'place_type',
    ],

    // This fields parameter enables you to select which specific poll fields will deliver in each returned Tweet.
    // https://developer.x.com/en/docs/x-api/data-dictionary/object-model/poll
    'poll.fields' => [
        'duration_minutes',
        'end_datetime',
        'id',
        'options',
        'voting_status',
    ],

    // This fields parameter enables you to select which specific place fields will deliver in each returned Tweet.
    // https://developer.x.com/en/docs/x-api/data-dictionary/object-model/user
    'user.fields' => [
        'created_at',
        'description',
        'entities',
        'id',
        'location',
        'most_recent_tweet_id',
        'name',
        'pinned_tweet_id',
        'profile_image_url',
        'protected',
        'public_metrics',
        'url',
        'username',
        'verified',
        'verified_type',
        'withheld',
    ],
];
