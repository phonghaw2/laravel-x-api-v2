<?php

namespace Phonghaw2\X\Exceptions;

use Exception;

class InvalidParameter extends Exception
{
    public static function invalidParameterValue(string $name, string $details = null)
    {
        $message = "Request validation failed: The '{$name}' parameter must be provided and contain valid values.";
        if ($details) {
            $message .= ". {$details}";
        }
        return new static($message);
    }

    public static function containWhitespace(string $name)
    {
        return new static("{$name} must not contain any whitespaces.");
    }

    public static function invalidArray(string $name)
    {
        return new static("{$name} must be an array.");
    }

    public static function invalidBoolean(string $name)
    {
        return new static("{$name} is not a valid boolean. Please provide true or false.");
    }

    public static function invalidElement(array $values, string $name)
    {
        return new static(sprintf('The value [%s] is invalid because it does not belong to the allowed %s.', implode(',', $values), $name));
    }

    public static function emptyRequestBody()
    {
        return new static("Post data cannot be empty.");
    }

    public static function invalidId(string $name)
    {
        return new static("{$name} must pass regular expression ^[0-9]{1,19}$");
    }

    public static function invalidUrl(string $name)
    {
        return new static("The {$name}  is invalid. Please enter a valid URL starting with http:// or https://.");
    }

    public static function notBetween(string $name, string $min, string $max)
    {
        return new static("{$name} must be a number between {$min} and {$max}.");
    }

    public static function invalidArgumentDatetime(string $name)
    {
        return new static("Invalid datetime format for {$name}. Expected format: Y-m-d H:i:s.");
    }

    public static function InvalidArgumentLength(string $name, int $min)
    {
        return new static("The {$name} must be at least {$min} character(s) long.");

    }

    public static function InvalidArgumentStringLength(string $name, string $min, string $max)
    {
        return new static("The {$name} string length must be between {$min} and {$max} characters.");
    }
}
