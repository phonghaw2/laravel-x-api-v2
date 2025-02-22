<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Post/Count Controller
 */
class Count extends AbstractXBuilder
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
     * Full archive search counts.
     * Returns Post Counts that match a search query.
     * @return Count
     */
    public function countAll()
    {
        $this->setEndpoint('tweets/counts/all');
        return $this->sendRequest();
    }

    /**
     * Recent search counts.
     * Returns Post Counts from the last 7 days that match a search query.
     * @return Count
     */
    public function countRecent()
    {
        $this->setEndpoint('tweets/counts/recent');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Count
     */
    public function setQueryParams(array $queryParams): Count
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
                case 'next_token':
                case 'pagination_token':
                    $this->validator->validateMinLength($value, $key);
                    break;
                case 'granularity':
                    $this->validator->validateGranularity($value);
                    break;
                case 'search_count.fields':
                    $value = $this->validator->validateField($value, $key);
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
