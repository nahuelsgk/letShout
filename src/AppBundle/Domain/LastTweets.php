<?php namespace AppBundle\Domain;

use AppBundle\Services\TwitterServiceInterface;

class LastTweets
{
    protected $tweet_service;

    /** @var array  */
    protected $errors;

    /** @var string */
    protected $screen_name;

    /** @var int */
    protected $count;

    /** @var null  */
    protected $tweets;

    /** @var  bool */
    protected $user_not_found;

    /** @var  bool */
    protected $no_connection;

    public function __construct(TwitterServiceInterface $tweet_service, $screen_name, $count)
    {
        $this->tweet_service  = $tweet_service;
        $this->errors         = [];
        $this->screen_name    = $screen_name;
        $this->count          = $count;
        $this->tweets         = null;
        $this->user_not_found = false;
        $this->no_connection  = false;
        $this->init();
    }

    public function init()
    {
        try {
            $response = $this->tweet_service->getUser($this->screen_name);
            if ($this->responseHasErrors($response)) {
                $this->errors[] = $response;
                $this->user_not_found = true;
            } else {
                $tweets       = $this->tweet_service->getLastTweets($this->screen_name, $this->count);
                $this->tweets = $tweets;
            }
        } catch (\Exception $e) {
            $this->no_connection = true;
            $this->errors[] = $e->getMessage();
        }

        
        return $this;
    }
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    private function responseHasErrors($response)
    {
        return array_key_exists('errors', $response);
    }

    /**
     * @return null
     */
    public function getTweets()
    {
        return $this->tweets;
    }

    /**
     * @return bool
     */
    public function userNotFound()
    {
        return $this->user_not_found;
    }

    /**
     * @return bool
     */
    public function hasConnectionProblems()
    {
        return $this->no_connection;
    }

}