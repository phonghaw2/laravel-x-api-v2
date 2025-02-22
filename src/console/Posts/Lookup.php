<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Posts/Lookup Controller
 */
class Lookup extends AbstractXBuilder
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
     * Post lookup by Post IDs
     * @return Lookup
     */
    public function lookup()
    {
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint('tweets');
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Post lookup by Post ID
     * Returns a variety of information about the Post specified by the requested ID.
     * @param int $postId
     * @return Lookup
     */
    public function lookupSinglePost(int $postId)
    {
        $this->validator->validateNumberId($postId, 'postId');
        $this->setEndpoint('tweets/' . $postId);
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Lookup
     */
    protected function setQueryParams(array $queryParams): Lookup
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
