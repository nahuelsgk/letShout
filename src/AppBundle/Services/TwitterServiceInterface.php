<?php namespace AppBundle\Services;

interface TwitterServiceInterface
{
    public function getUser($screen_name);

    public function getLastTweets($screen_name, $count);
}