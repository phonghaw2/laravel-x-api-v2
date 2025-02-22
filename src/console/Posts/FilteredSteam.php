<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Post/FilteredSteam Controller
 */
class FilteredSteam extends AbstractXBuilder
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
     * @return FilteredSteam
     */
    public function FilteredSteam()
    {
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint('tweets/search/stream');
        return $this->sendRequest();
    }

    /**
     * Rules lookup.
     * Returns rules from a User’s active rule set. Users can fetch all of their rules or a subset, specified by the provided rule ids.
     * @return FilteredSteam
     */
    public function rulesLookup()
    {
        $this->setEndpoint('tweets/search/stream/rules');
        return $this->sendRequest();
    }

    /**
     * Rules Count.
     * Returns the counts of rules from a User’s active rule set, to reflect usage by project and application.
     * @return FilteredSteam
     */
    public function rulesCount()
    {
        $this->setEndpoint('tweets/search/stream/rules/counts');
        return $this->sendRequest();
    }

    /**
     * Add rules.
     * Add rules from a User’s active rule set. Users can provide unique, optionally tagged rules to add.
     * @return FilteredSteam
     */
    public function addRule()
    {
        $this->setEndpoint('tweets/search/stream/rules');
        return $this->sendRequest();
    }

     /**
     * Delete rules.
     * Users can delete their entire rule set or a subset specified by rule ids or values..
     * @return FilteredSteam
     */
    public function deleteRule()
    {
        $this->validator->validateRequestBodyDeleteRule($this->postData);
        $this->setEndpoint('tweets/search/stream/rules');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return TweetLookup
     */
    public function setQueryParams(array $queryParams): FilteredSteam
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'rules_count.fields':
                    $value = $this->validator->validateField($value, $key);
                    break;
                case 'dry_run':
                case 'delete_all':
                    $this->validator->validateBoolean($value, $key);
                    $value = $value ? 'true' : 'false';
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
