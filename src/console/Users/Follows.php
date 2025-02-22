<?php

namespace Phonghaw2\X\Console\Users;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class User/Follows Controller
 */
class Follows extends AbstractXBuilder
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
     * Followers by User ID
     * Returns a list of Users who are followers of the specified User ID.
     * @param int $user
     * @return Follows
     */
    public function followerByUserId(int $userId)
    {
        $this->validator->validateNumberId($userId, 'userId');
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/{$userId}/followers");
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Following by User ID
     * Returns a list of Users that are being followed by the provided User ID
     * @param int $user
     * @return Follows
     */
    public function followingByUserId(int $userId)
    {
        $this->validator->validateNumberId($userId, 'userId');
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/{$userId}/following");
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Following by User ID
     * Returns a list of Users that are being followed by the provided User ID
     * @param int $user
     * @return Follows
     */
    public function followUser(int $userId)
    {
        $this->validator->validateNumberId($userId, 'userId');
        $this->validator->validateRequestBodyFollowingUser($this->postData);
        $this->setEndpoint("/users/{$userId}/following");
        $this->setHttpRequestMethod('POST');
        return $this->sendRequest();
    }

    /**
     * Unfollow User
     * @param int $source_user_id
     * @param int $target_user_id
     *
     * @return Follows
     */
    public function unfollowUser(int $source_user_id, int $target_user_id)
    {
        $this->validator->validateNumberId($source_user_id, 'source_user_id');
        $this->validator->validateNumberId($target_user_id, 'target_user_id');
        $this->setEndpoint("/users/{$source_user_id}/following/{$target_user_id}");
        $this->setHttpRequestMethod('DELETE');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Follows
     */
    public function setQueryParams(array $queryParams): Follows
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'query' :
                    $this->validator->validateBetweenLength($value, 1, 4096, $key);
                    break;
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
