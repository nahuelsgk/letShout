<?php namespace AppBundle\Services;

class TwitterService implements TwitterServiceInterface
{
    protected $settings;

    protected $api_exchange;

    public function __construct(
        $oauth_access_token,
        $oauth_access_token_secret,
        $consumer_key,
        $consumer_secret
    ) {
        $settings = array(
            'oauth_access_token'        => $oauth_access_token,
            'oauth_access_token_secret' => $oauth_access_token_secret,
            'consumer_key'              => $consumer_key,
            'consumer_secret'           => $consumer_secret
        );
        $this->api_exchange = new \TwitterAPIExchange($settings);
    }

    public function getUser($screen_name)
    {
        $twitter       = $this->api_exchange;
        $url           = 'https://api.twitter.com/1.1/users/show.json';
        $get           = '?screen_name=' . $screen_name;
        $requestMethod = 'GET';

        $profile = $twitter
            ->setGetfield($get)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        $response = json_decode($profile);

        return $response;
    }

    public function getLastTweets($screen_name, $count)
    {
        $twitter       = $this->api_exchange;
        $url           = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $get           = '?screen_name=' . $screen_name . '&count=' . $count;
        $requestMethod = 'GET';

        $tweets = $twitter->setGetfield($get)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
        
        $tweets = json_decode($tweets);

        return $tweets;
    }
}