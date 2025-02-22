<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Posts/Bookmarks Controller
 */
class Bookmarks extends AbstractXBuilder
{
    /**
     * @param array<string> $settings
     * @throws \Exception
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
        // The access token received from the authorization server in the OAuth 2.0 flow.
        $this->setAuthMode(0);
    }

    /**
     * Bookmarks by User
     * Returns Post objects that have been bookmarked by the requesting User
     * @param int|string $userId
     *
     * @return Bookmarks
     */
    public function bookmarksByUser($userId)
    {
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint("/users/{$userId}/bookmarks");
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Add Post to Bookmarks
     * Adds a Post (ID in the body) to the requesting User’s (in the path) bookmarks
     * @param int|string $userId
     *
     * @return Bookmarks
     */
    public function addPostToBookmarks($userId)
    {
        $this->validator->validateRequestBody($this->postData);
        $this->setEndpoint("/users/{$userId}/bookmarks");
        $this->setHttpRequestMethod('POST');
        return $this->sendRequest();
    }

    /**
     * Remove a bookmarked Post
     * Removes a Post from the requesting User’s bookmarked Posts.
     * @param int|string $userId
     * @param int|string $tweetId
     *
     * @return Bookmarks
     */
    public function removeBookmarks($userId, $tweetId)
    {
        $this->setEndpoint("/users/{$userId}/bookmarks/{$tweetId}");
        $this->setHttpRequestMethod('DELETE');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Bookmarks
     */
    protected function setQueryParams(array $queryParams): Bookmarks
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'expansions':
                    $value = $this->validator->validateExpansion($value);
                    break;
                case 'tweet.fields':
                case 'media.fields':
                case 'poll.fields':
                case 'user.fields':
                case 'place.fields':
                    $value = $this->validator->validateField($value, $key);
                    break;
                case 'max_results':
                    $this->validator->validateBetween($value, 1, 100, $key);
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
