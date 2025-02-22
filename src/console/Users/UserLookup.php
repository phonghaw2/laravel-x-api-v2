<?php

namespace Phonghaw2\X\Console\Users;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class User/Lookup Controller
 */
class UserLookup extends AbstractXBuilder
{
    /**
     * @param array<string> $settings
     * @throws \Exception
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
        $this->setAuthMode(0);
        $this->setHttpRequestMethod('GET');
    }

    /**
     * User lookup by ID
     * This endpoint returns information about a User. Specify User by ID.
     * @param int $id
     * @return UserLookup
     */
    public function lookupSingleId(int $id)
    {
        $this->validator->validateNumberId($id, 'User id');
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/{$id}");
        return $this->sendRequest();
    }

    /**
     * User lookup by IDs
     * This endpoint returns information about Users. Specify Users by their ID.
     *
     * @return UserLookup
     */
    public function lookup()
    {
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users");
        return $this->sendRequest();
    }

    /**
     * User lookup by username
     * This endpoint returns information about a User. Specify User by username.
     * @param string $username
     * @return UserLookup
     */
    public function lookupByUserName(string $username)
    {
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/by/username/{$username}");
        return $this->sendRequest();
    }

    /**
     * User lookup by usernames
     * This endpoint returns information about Users. Specify Users by their username.
     *
     * @return UserLookup
     */
    public function lookupByUserNames()
    {
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/by");
        return $this->sendRequest();
    }

    /**
     * User lookup by usernames
     * This endpoint returns information about Users. Specify Users by their username.
     *
     * @return UserLookup
     */
    public function lookupMe()
    {
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/me");
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return UserLookup
     */
    public function setQueryParams(array $queryParams): UserLookup
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'ids':
                    $value = $this->validator->validateIds($value);
                    break;
                case 'usernames':
                    $value = $this->validator->validateUsernames($value);
                    break;
                case 'tweet.fields':
                case 'user.fields':
                    $value = $this->validator->validateField($value, $key);
                    break;
                case 'expansions':
                    $value = $this->validator->validateExpansion($value);
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
