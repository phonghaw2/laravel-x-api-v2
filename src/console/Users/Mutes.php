<?php

namespace Phonghaw2\X\Console\Users;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class User/Mutes Controller
 */
class Mutes extends AbstractXBuilder
{
    /**
     * @param array<string> $settings
     * @throws \Exception
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
        $this->setAuthMode(0);
    }

    /**
     * User Mutes
     * Returns Users that match a Mutes query.
     * @return Mutes
     */
    public function mutes(int $userId)
    {
        $this->validator->validateNumberId($userId, 'userId');
        $this->validator->validateRequestBodyFollowingUser($this->postData);
        $this->setEndpoint("/users/{$userId}/muting");
        $this->setHttpRequestMethod('POST');
        return $this->sendRequest();
    }

    /**
     * Unmute User by User ID
     * @param int $source_user_id
     * @param int $target_user_id
     *
     * @return Mutes
     */
    public function unmuteUser(int $source_user_id, int $target_user_id)
    {
        $this->validator->validateNumberId($source_user_id, 'source_user_id');
        $this->validator->validateNumberId($target_user_id, 'target_user_id');
        $this->setEndpoint("/users/{$source_user_id}/muting/{$target_user_id}");
        $this->setHttpRequestMethod('DELETE');
        return $this->sendRequest();
    }

    /**
     * Returns User objects that are muted by the provided User ID
     * Returns a list of Users that are muted by the provided User ID
     * @return Mutes
     */
    public function getInfoUserMuted(int $userId)
    {
        $this->validator->validateNumberId($userId, 'userId');
        $this->setEndpoint("/users/{$userId}/muting");
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Mutes
     */
    public function setQueryParams(array $queryParams): Mutes
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'tweet.fields':
                case 'user.fields':
                    $value = $this->validator->validateField($value, $key);
                    break;
                case 'expansions':
                    $value = $this->validator->validateExpansion($value);
                    break;
                case 'max_results':
                    $this->validator->validateBetween($value, 1, 1000, $key);
                    break;
                case 'next_token':
                case 'pagination_token':
                        $this->validator->validateMinLength($value, $key);
                        break;
                default:
                    $continueLoop = true;
                    break;

            }
            if ($continueLoop) continue;
            $query[] = "{$key}=" . $value;
        }

        if (count($query) > 0) {
            $this->queryString = implode('&', $query);
        }
        return $this;
    }
}
