<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Posts/Manage Controller
 */
class Manage extends AbstractXBuilder
{
    /**
     * @param array<string> $settings
     * @throws \Exception
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
        $this->setAuthMode(1);
    }

    /**
     * Post lookup by Post IDs
     * @see https://developer.twitter.com/en/docs/twitter-api/tweets/manage-tweets/api-reference/post-tweets
     * @return Manage
     */
    public function create()
    {
        $this->validator->validateRequestBodyCreatePost($this->postData);
        $this->setEndpoint('tweets');
        $this->setHttpRequestMethod('POST');
        return $this->sendRequest();
    }

    /**
     * Delete a Tweet.
     * @param int $postId
     * @return Manage
     */
    public function delete(int $postId)
    {
        $this->validator->validateNumberId($postId, 'postId');
        $this->setEndpoint('tweets/' . $postId);
        $this->setHttpRequestMethod('DELETE');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Manage
     */
    protected function setQueryParams(array $queryParams): Manage
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'ids':
                    $value = $this->validator->validateIds($value);
                    break;
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
                case 'exclude':
                    $this->validator->validateExclude($value);
                    break;
                case 'max_results':
                    $this->validator->validateBetween($value, 1, 100, $key);
                    break;
                case 'days':
                    $this->validator->validateBetween($value, 1, 90, $key);
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
