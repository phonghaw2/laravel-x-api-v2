<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Tweet/Reposts Controller
 */
class Reposts extends AbstractXBuilder
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
     * Returns User objects that have retweeted the provided Post ID
     * Returns a list of Users that have retweeted the provided Post ID
     * @param int $postId
     * @return Reposts
     */
    public function getUserHaveRetweeted(int $postId)
    {
        $this->validator->validateNumberId($postId, 'postId');
        if (!empty($this->queryParams['expansions'])) {
            $this->validator->validateExpansionRetweet($this->queryParams['expansions']);
        }
        $this->setEndpoint('tweets/' . $postId . '/retweeted_by');
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Causes the User (in the path) to repost the specified Post. The User in the path must match the User context authorizing the request.
     * @param int|string $userId The ID of the authenticated source User that is requesting to repost the Post.
     * @return Reposts
     */
    public function repostPostForUser($userId)
    {
        $this->validator->validateRequestBody($this->postData);
        $this->setEndpoint("/users/{$userId}/retweets");
        $this->setHttpRequestMethod('POST');
        return $this->sendRequest();
    }

    /**
     * Causes the User (in the path) to unretweet the specified Post. The User must match the User context authorizing the request
     * @param int|string $userId The ID of the authenticated source User that is requesting to repost the Post.
     * @param int|string $source_tweet_id The ID of the Post that the User is requesting to unretweet.
     * @return Reposts
     */
    public function deleteRetweetForUser($userId, $source_tweet_id)
    {
        $this->setEndpoint("/users/{$userId}/retweets/{$source_tweet_id}");
        $this->setHttpRequestMethod('DELETE');
        return $this->sendRequest();
    }

    /**
     * Retrieve Posts that repost a Post.
     * @param int $postId A single Post ID.
     * @return Reposts
     */
    public function getRetweets(int $postId)
    {
        $this->validator->validateNumberId($postId, 'postId');
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint('tweets/' . $postId . '/retweets');
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Returns repost of user
     * This endpoint returns reposts of the requesting User.
     * @return Reposts
     */
    public function getReposts()
    {
        $this->setEndpoint("/users/reposts_of_me");
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Reposts
     */
    protected function setQueryParams(array $queryParams): Reposts
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
