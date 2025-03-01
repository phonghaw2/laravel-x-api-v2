<?php

namespace Phonghaw2\X\Console;

use Config;

class Adapter
{
    /**
     * Twitter API settings.
     * @var array<string>
     */
    protected array $settings = [];

    /**
     * Client initialization.
     * @param array<string> $settings
     */
    public function __construct()
    {
        $this->settings = Config::get('twitter', []);
    }

    /**
     * Access to Retweet endpoints.
     * @return Retweet
     * @throws \Exception
     */
    public function retweet(): Retweet
    {
        return new Retweet($this->settings);
    }

    /**
     * Access to Timeline endpoints.
     * @return Timeline
     * @throws \Exception
     */
    public function timeline(): Timeline
    {
        return new Timeline($this->settings);
    }

    /**
     * Access to Tweet endpoints.
     * @return TweetManage
     * @throws \Exception
     */
    public function tweet(): TweetManage
    {
        return new TweetManage($this->settings);
    }

    /**
     * Access to Tweet/Likes endpoints.
     * @return TweetLikes
     * @throws \Exception
     */
    public function tweetLikes(): TweetLikes
    {
        return new TweetLikes($this->settings);
    }

    /**
     * Access to Tweet/Lookup endpoints.
     * @return TweetLookup
     * @throws \Exception
     */
    public function tweetLookup(): TweetLookup
    {
        return new TweetLookup($this->settings);
    }

    /**
     * Access to Tweet/Quotes endpoints.
     * @return TweetQuotes
     * @throws \Exception
     */
    public function tweetQuotes(): TweetQuotes
    {
        return new TweetQuotes($this->settings);
    }

    /**
     * Access to Tweet/Replies endpoints.
     * @return TweetReplies
     * @throws \Exception
     */
    public function tweetReplies(): TweetReplies
    {
        return new TweetReplies($this->settings);
    }

    /**
     * Access To User/Blocks endpoints.
     * @return UserBlocks
     * @throws \Exception
     */
    public function userBlocks(): UserBlocks
    {
        return new UserBlocks($this->settings);
    }

    /**
     * Access To User/Follows endpoints.
     * @return UserFollows
     * @throws \Exception
     */
    public function userFollows(): UserFollows
    {
        return new UserFollows($this->settings);
    }

    /**
     * Access To User/Lookup endpoints.
     * @return UserLookup
     * @throws \Exception
     */
    public function userLookup(): UserLookup
    {
        return new UserLookup($this->settings);
    }

    /**
     * Access To User/Mutes endpoints.
     * @return UserMutes
     * @throws \Exception
     */
    public function userMutes(): UserMutes
    {
        return new UserMutes($this->settings);
    }
}
