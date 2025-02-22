<?php

namespace Phonghaw2\X\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function accountIdNotSpecified()
    {
        return new static('There was no account_id specified. You must provide a valid account_id to execute queries on Twitter API.');
    }

    public static function endpointNotValid()
    {
        return new static('API_BASE_URI is invalid. Please check the API endpoint and ensure it is in the correct format.');
    }

    public static function authenticationMethodNotSupported()
    {
        return new static('Not ready to support other authentication methods.');
    }

    public static function credentialsJsonDoesNotExist(string $path)
    {
        return new static("Could not find a credentials file at `{$path}`.");
    }

}
