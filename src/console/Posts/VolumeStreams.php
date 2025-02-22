<?php

namespace Phonghaw2\X\Console\Posts;

use Phonghaw2\X\Console\AbstractXBuilder;
use Phonghaw2\X\Exceptions\InvalidParameter;

/**
 * Class Posts/VolumeStreams Controller
 */
class VolumeStreams extends AbstractXBuilder
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
     * Firehose stream
     * Streams 100% of public Posts.
     * @return VolumeStreams
     */
    public function firehoseStream()
    {
        $this->validator->validatePartition($this->queryParams, 1, 20);
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint("/tweets/firehose/stream");
        return $this->sendRequest();
    }

    /**
     * Language Firehose stream
     * Streams 100% of Language public Posts.
     * @param string $lang
     * @return VolumeStreams
     */
    public function firehoseStreamByLanguage(string $lang)
    {
        switch ($lang) {
            case 'en':
                $minPartition = 1;
                $maxPartition = 8;
                break;
            case 'ko':
            case 'ja':
            case 'pt':
                $minPartition = 1;
                $maxPartition = 2;
                break;
            default:
                throw InvalidParameter::invalidElement(['en', 'ja', 'ko', 'pt'], $lang);
        }
        $this->validator->validatePartition($this->queryParams, $minPartition, $maxPartition);
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint("/tweets/firehose/stream/lang/{$lang}");
        return $this->sendRequest();
    }

    /**
     * Sample stream
     * Streams a deterministic 1% of public Posts.
     * @return VolumeStreams
     */
    public function sampleStream()
    {
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint("/tweets/sample/stream");
        return $this->sendRequest();
    }

    /**
     * Sample 10% stream
     * Streams a deterministic 10% of public Posts.
     * @return VolumeStreams
     */
    public function sample10Stream()
    {
        $this->validator->validatePartition($this->queryParams, 1, 2);
        $this->validator->validateExpansionCommon($this->queryParams);
        $this->setEndpoint("/tweets/sample/stream");
        return $this->sendRequest();
    }

    /**
     * Set Query parameter
     * @param array $queryParams
     * @return VolumeStreams
     */
    public function setQueryParams(array $queryParams): VolumeStreams
    {
        $query = [];
        $this->queryParams = $queryParams;

        foreach ($queryParams as $key => $value) {
            $continueLoop = false;
            switch ($key) {
                case 'backfill_minutes':
                    $this->validator->validateBetween($value, 0, 5, $key);
                    break;
                case 'start_time':
                case 'end_time':
                    $value = $this->validator->validateDatetime($value, $key);
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
