<?php

namespace Phonghaw2\X\Console\Users;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class User/Search Controller
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
     * User search
     * Returns Users that match a search query.
     * @return Search
     */
    public function search()
    {
        $this->validator->validateExpansionLookup($this->queryParams);
        $this->setEndpoint("/users/search");
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Search
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
