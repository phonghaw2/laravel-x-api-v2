<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Post/Search Controller
 */
class Quotes extends AbstractXBuilder
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
     * Retrieve Posts that quote a Post.
     * @param int $postId
     * @return Quotes
     */
    public function getQuoteTweets(int $postId)
    {
        $this->validator->validateNumberId($postId, 'postId');
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint("tweets/{$postId}/quote_tweets");
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Quotes
     */
    public function setQueryParams(array $queryParams): Quotes
    {
        $query = [];
        $this->queryParams = $queryParams;
        $this->validator->validateRequireQuery($queryParams);

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'max_results':
                    $this->validator->validateBetween($value, 10, 100, $key);
                    break;
                case 'exclude':
                    $this->validator->validateExclude($value);
                    break;
                case 'pagination_token':
                    $this->validator->validateMinLength($value, $key);
                    break;
                case 'tweet.fields':
                case 'media.fields':
                case 'poll.fields':
                case 'user.fields':
                case 'place.fields':
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
