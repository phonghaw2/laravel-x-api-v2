<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Posts/Search Controller
 */
class Search extends AbstractXBuilder
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
     * Full-archive search.
     * Returns Posts that match a search query.
     * @return Search
     */
    public function searchAll()
    {
        $this->setEndpoint('tweets/search/all');
        return $this->sendRequest();
    }

    /**
     * Recent search.
     * Returns Posts from the last 7 days that match a search query.
     * @return Search
     */
    public function searchRecent()
    {
        $this->setEndpoint('tweets/search/recent');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return TweetLookup
     */
    public function setQueryParams(array $queryParams): Search
    {
        $query = [];
        $this->queryParams = $queryParams;
        $this->validator->validateRequireQuery($queryParams);

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'query' :
                    $this->validator->validateBetweenLength($value, 1, 4096, $key);
                    break;
                case 'start_time':
                case 'end_time':
                    $value = $this->validator->validateDatetime($value, $key);
                    break;
                case 'since_id':
                case 'until_id':
                    $this->validator->validateNumberId($value, $key);
                    break;
                case 'max_results':
                    $this->validator->validateBetween($value, 10, 500, $key);
                    break;
                case 'days':
                    $this->validator->validateBetween($value, 1, 90, $key);
                    break;
                case 'next_token':
                case 'pagination_token':
                    $this->validator->validateMinLength($value, $key);
                    break;
                case 'sort_order':
                    $this->validator->validateSortOrder($value);
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
