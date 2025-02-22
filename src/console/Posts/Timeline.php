<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;

/**
 * Class Post/Timeline Controller
 */
class Timeline extends AbstractXBuilder
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
     * User Posts timeline by User ID
     * Returns a list of Posts authored by the provided User ID
     * @param int userId
     *
     * @return Timeline
     */
    public function getUserPostTimeline(int $userId)
    {
        $this->validator->validateNumberId($userId, 'id');
        $this->setEndpoint('users/' . $userId . '/tweets');
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * User mention timeline by User ID
     * Returns Post objects that mention username associated to the provided User ID
     * @param int userId
     *
     * @return Timeline
     */
    public function getUserMentionTimeline(int $userId)
    {
        $this->validator->validateNumberId($userId, 'id');
        $this->setEndpoint('users/' . $userId . '/mentions');
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * User home timeline by User ID
     * Returns Post objects that appears in the provided User ID’s home timeline
     * @param int userId
     *
     * @return Timeline
     */
    public function getUserHomeTimeline(int $userId)
    {
        $this->validator->validateNumberId($userId, 'id');
        $this->setEndpoint('users/' . $userId . '/timelines/reverse_chronological');
        $this->setHttpRequestMethod('GET');
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return Timeline
     */
    protected function setQueryParams(array $queryParams): Timeline
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'since_id':
                case 'until_id':
                    $value = $this->validator->validateNumberId($value, $key);
                    break;
                case 'expansions':
                    $value = $this->validator->validateExpansion($value);
                    break;
                case 'start_time':
                case 'end_time':
                    $value = $this->validator->validateDatetime($value, $key);
                    break;
                case 'max_results':
                    $this->validator->validateBetween($value, 5, 100, $key);
                    break;
                case 'next_token':
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
                case 'exclude':
                    $this->validator->validateExclude($value);
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
