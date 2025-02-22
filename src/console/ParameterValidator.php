<?php

namespace Phonghaw2\X\Console;

use Carbon\Carbon;
use Phonghaw2\X\Exceptions\InvalidParameter;

class ParameterValidator
{
    protected mixed $resultValidate = null;

    public function validateRequireQuery($queryParams)
    {
        if (!isset($queryParams['query']) || $queryParams['query'] === '') {
            throw InvalidParameter::invalidParameterValue('query');
        }
    }

    public function validatePartition($queryParams, $min, $max)
    {
        if (!isset($queryParams['partition'])) {
            throw InvalidParameter::invalidParameterValue('partition');
        }
        $this->validateBetween($queryParams['partition'], $min, $max, 'partition');
    }

    /**
     * Validate ID parameter
     *
     * @throws InvalidParameterException
     */
    public function validateIds(mixed $value): string
    {
        $ids = '';
        if (is_array($value)) {
            $ids = implode(',', $value);
        }
        if (is_string($value)) {
            $ids = $value;
        }
        if ($ids === '') {
            throw InvalidParameter::invalidParameterValue('ids');
        }

        $this->validateNoWhitespace($ids, 'ids');

        return $ids;
    }

    /**
     * Validate username parameter
     *
     * @throws InvalidParameterException
     */
    public function validateUsernames(mixed $value): string
    {
        $usernames = '';
        if (is_array($value)) {
            $usernames = implode(',', $value);
        }
        if (is_string($value)) {
            $usernames = $value;
        }
        if ($usernames === '') {
            throw InvalidParameter::invalidParameterValue('usernames');
        }

        $this->validateNoWhitespace($usernames, 'usernames');

        return $usernames;
    }

    /**
     * Validate expansion
     *
     * @throws InvalidParameterException
     */
    public function validateExpansion($expansions): string
    {
        if (!is_array($expansions)) {
            throw InvalidParameter::invalidArray('expansions');
        }

        return implode(',', $expansions);
    }

    /**
     * Validate expansion
     *
     * @throws InvalidParameterException
     */
    public function validateField($fields, $name): string
    {
        if (!is_array($fields)) {
            throw InvalidParameter::invalidArray($name);
        }

        $validFields = config("x.{$name}", []);
        $this->validateArray($fields, $validFields);

        if (!$this->resultValidate->isValid) {
            throw InvalidParameter::invalidElement($this->resultValidate->invalidArray, $name);
        }

        return implode(',', $fields);
    }

    /**
     * Validate string parameter has no whitespace
     */
    public function validateNoWhitespace(string $value, string $paramName): void
    {
        if (preg_match('/\s/', $value)) {
            throw InvalidParameter::containWhitespace($paramName);
        }
    }

    /**
     * Validate expanded object metadata for lookup
     *
     * @throws InvalidParameterException
     */
    public function validateExpansionCommon(array $queryParams): void
    {
        if (empty($queryParams['expansions'])) {
            return;
        }
        $this->validateArray($queryParams['expansions'], config('x.expansion.common'), []);
        if (!$this->resultValidate->isValid) {
            throw InvalidParameter::invalidElement($this->resultValidate->invalidArray, 'expansions');
        }
    }

    public function validateExpansionRetweet(array $queryParams): void
    {
        if (empty($queryParams['expansions'])) {
            return;
        }
        $this->validateArray($queryParams['expansions'], config('x.expansion.retweeted_by'), []);
        if (!$this->resultValidate->isValid) {
            throw InvalidParameter::invalidElement($this->resultValidate->invalidArray, 'expansions');
        }
    }

    public function validateExpansionLookup(array $queryParams): void
    {
        if (empty($queryParams['expansions'])) {
            return;
        }
        $this->validateArray($queryParams['expansions'], config('x.expansion.lookup'), []);
        if (!$this->resultValidate->isValid) {
            throw InvalidParameter::invalidElement($this->resultValidate->invalidArray, 'expansions');
        }
    }

    public function validateArray($inputArray, $validArray)
    {
        $invalid = [];
        if (count($validArray) > 0) {
            foreach ($inputArray as $value) {
                if (!in_array($value, $validArray)) {
                    $invalid[] = $value;
                }
            }
        }

        $this->resultValidate = (object)[
            'isValid' => count($invalid) == 0,
            'invalidArray' => $invalid
        ];
    }

    public function validateNumberId($value, $name)
    {
        if (!preg_match('/^[0-9]{1,19}$/', $value)) {
            throw InvalidParameter::invalidId($name);
        }
    }

    public function validateLink($url, $name)
    {
        if (!preg_match('/\b(?:https?|ftp):\/\/(?:www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(?:\/[^\s]*)?\b/', $url)) {
            throw InvalidParameter::invalidUrl($name);
        }
    }

    public function validateBoolean($value, $name)
    {
        if (!is_bool($value)) {
            throw InvalidParameter::invalidBoolean($name);
        }
    }

    public function validateBetween($value, $min, $max, $name)
    {
        if (!is_numeric($value) || !($value >= $min && $value <= $max)) {
            throw InvalidParameter::notBetween($name, $min, $max);
        }
    }

    public function validateExclude($value)
    {
        $validOptions = [
            'replies',
            'retweets',
        ];
        if (!in_array($value, $validOptions)) {
            throw InvalidParameter::invalidParameterValue('exclude');
        }
    }

    public function validateSortOrder($value)
    {
        $validOptions = [
            'recency',
            'relevancy',
        ];
        if (!in_array($value, $validOptions)) {
            throw InvalidParameter::invalidParameterValue('sort_order');
        }
    }

    public function validateGranularity($value)
    {
        $validOptions = [
            'minute',
            'hour',
            'day',
        ];
        if (!in_array($value, $validOptions)) {
            throw InvalidParameter::invalidParameterValue('granularity');
        }
    }

    public function validateDatetime($value, $name)
    {
        $valueDate = Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');
        if ($valueDate === false) {
            throw InvalidParameter::invalidArgumentDatetime($name);
        }
        return $valueDate->toISOString();
    }

    public function validateMinLength($value, $name, $min = 1)
    {
        if (strlen($value) < $min) {
            throw InvalidParameter::InvalidArgumentLength($name, $min);
        }
    }

    public function validateBetweenLength($value, $min, $max, $name)
    {
        if (strlen($value) < $min || strlen($value) > $max) {
            throw InvalidParameter::InvalidArgumentStringLength($name, $min, $max);
        }
    }

    public function validateRequestBodyDeleteRule($postData)
    {
        if (!empty($postData)) {
            throw InvalidParameter::emptyRequestBody();
        }

        foreach ($postData as $key => $value) {
            switch ($key) {
                case 'ids':
                    if (!is_array($value)) {
                        throw InvalidParameter::invalidArray($key);
                    }
                    foreach ($value as $v) {
                        $this->validateNumberId($v, $key);
                    }
                    break;
                case 'values':
                    if (!is_array($value)) {
                        throw InvalidParameter::invalidArray($key);
                    }
                    break;
            }
        }
    }

    public function validateRequestBodyCreatePost($postData)
    {
        if (!empty($postData)) {
            throw InvalidParameter::emptyRequestBody();
        }

        foreach ($postData as $key => $value) {
            switch ($key) {
                case 'quote_tweet_id':
                case 'community_id':
                    $this->validateNumberId($value, $key);
                    break;
                case 'direct_message_deep_link':
                    $this->validateLink($value, $key);
                    break;
                case 'nullcast':
                case 'for_super_followers_only':
                    $this->validateBoolean($value, $key);
                    break;
                case 'geo':
                    if (!is_array($value)) {
                        throw InvalidParameter::invalidArray($key);
                    }
                    break;
                case 'media':
                    if (!is_array($value)) {
                        throw InvalidParameter::invalidArray($key);
                    }
                    if (isset($value['media_ids'])) {
                        if (!is_array($value['media_ids'])) {
                            throw InvalidParameter::invalidArray("{$key}.tagged_user_ids");
                        }
                        foreach ($value['media_ids'] as $v) {
                            $this->validateNumberId($v, "{$key}.media_ids");
                        }
                    }
                    if (isset($value['tagged_user_ids'])) {
                        if (!is_array($value)) {
                            throw InvalidParameter::invalidArray("{$key}.tagged_user_ids");
                        }
                        foreach ($value['tagged_user_ids'] as $v) {
                            $this->validateNumberId($v, "{$key}.tagged_user_ids");
                        }
                    }
                    break;
                case 'poll':
                    if (!is_array($value)) {
                        throw InvalidParameter::invalidArray($key);
                    }
                    if (isset($value['duration_minutes'])) {
                        $this->validateBetween($value['duration_minutes'], 5, 10080, "{$key}.duration_minutes");
                    }
                    if (isset($value['options'])) {
                        if (!is_array($value)) {
                            throw InvalidParameter::invalidArray("{$key}.options");
                        }
                    }
                    if (isset($value['reply_settings'])) {
                        $validSetting = [
                            'mentionedUsers',
                            'following',
                        ];
                        if (!in_array($value['reply_settings'], $validSetting)) {
                            throw InvalidParameter::invalidElement($validSetting, "{$key}.reply_settings");
                        }
                    }
                    break;
                case 'reply':
                    if (!is_array($value)) {
                        throw InvalidParameter::invalidArray($key);
                    }
                    if (isset($value['exclude_reply_user_ids'])) {
                        if (!is_array($value['exclude_reply_user_ids'])) {
                            throw InvalidParameter::invalidArray("{$key}.exclude_reply_user_ids");
                        }
                        foreach ($value['exclude_reply_user_ids'] as $v) {
                            $this->validateNumberId($v, "element of {$key}.exclude_reply_user_ids");
                        }
                    }
                    if (isset($value['in_reply_to_tweet_id'])) {
                        $this->validateNumberId($value['in_reply_to_tweet_id'], "{$key}.in_reply_to_tweet_id");
                    }
                    break;
                case 'reply_settings':
                    $validSetting = [
                        'mentionedUsers',
                        'following',
                        'subscribers',
                    ];
                    if (!in_array($value, $validSetting)) {
                        throw InvalidParameter::invalidElement($validSetting, $key);
                    }
                    break;
            }
        }
    }

    public function validateRequestBody($postData)
    {
        if (!empty($postData)) {
            throw InvalidParameter::emptyRequestBody();
        }

        foreach ($postData as $key => $value) {
            switch ($key) {
                case 'tweet_id':
                    $this->validateNumberId($value, $key);
                    break;
            }
        }
    }

    public function validateRequestBodyFollowingUser($postData)
    {
        if (!empty($postData)) {
            throw InvalidParameter::emptyRequestBody();
        }

        foreach ($postData as $key => $value) {
            switch ($key) {
                case 'target_user_id':
                    $this->validateNumberId($value, $key);
                    break;
            }
        }
    }
}
