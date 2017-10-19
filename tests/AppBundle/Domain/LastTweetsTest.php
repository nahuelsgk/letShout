<?php namespace Tests\AppBundle\Domain;

use AppBundle\Domain\LastTweets;
use AppBundle\Service\TwitterServiceInterface;
use PHPUnit\Framework\TestCase;

class LastTweetsTest extends TestCase
{
    public function testNonExistingUserShouldReturnErrorsWithMock()
    {
        /** @var TwitterServiceInterface $twitter_service */
        $twitter_service = \Mockery::mock(TwitterServiceInterface::class);
        $twitter_service
            ->shouldReceive('getUser')
            ->andReturn(
                [
                    "errors" => [
                        [
                            "code" => 50,
                            "message" => "User not found"
                        ]
                    ]
                ]
            );
        $last_tweets = new LastTweets($twitter_service, "pennywise", 10);
        $this->assertTrue($last_tweets->userNotFound());
        $this->assertTrue(count($last_tweets->getErrors()) > 0);
    }

    public function testGetLastTweetsSuccessfully()
    {
        /** @var TwitterServiceInterface $twitter_service */
        $twitter_service = \Mockery::mock(TwitterServiceInterface::class);
        $twitter_service
            ->shouldReceive('getUser')
            ->with('nahuelsgk')
            ->andReturn(
                [
                    "id"               => 122356710,
                    "id_str"           => "122356710",
                    "name"             => "Nahuel Velazco",
                    "screen_name"      => "nahuelsgk",
                    "location"         => "Barcelona",
                    "profile_location" => null,
                    "description"      => "Love and passion for ICTs, Music & Turntablism, comics and kendo.",
                ]
            );
        $twitter_service
            ->shouldReceive('getLastTweets')
            ->with('nahuelsgk', 2)
            ->andReturn(
                [
                    [
                        "created_at" => "Wed Oct 18 06:41:31 +0000 2017",
                        "id"         => 920540328700121088,
                        "id_str"     => "920540328700121088",
                        "text"       => "RT @Pablo_Iglesias_: Mi maestro @ManoloMonereo ha dado hoy una lección de política desde la tribuna del Congreso. Simplemente magistral👇\n\nh…",
                        "truncated"  => false
                    ],
                    [
                        "created_at" => "Wed Oct 18 06:20:16 +0000 2017",
                        "id"         => 920534978051665920,
                        "id_str"     => "920534978051665920",
                        "text"       => "RT @sitgesfestival: Envien a presó gent pacífica que organitza manifestacions pacífiques? Pensàvem que el Festival havia acabat ahir! #Real…",
                    ]
                ]
            );

        $last_tweets = new LastTweets($twitter_service, 'nahuelsgk', 2);
        $this->assertFalse(count($last_tweets->getErrors()) > 0);
        $last_tweets->getTweets();
        $this->assertCount(2, $last_tweets->getTweets());
    }

    public function testNoConnection()
    {
        /** @var TwitterServiceInterface $twitter_service */
        $twitter_service = \Mockery::mock(TwitterServiceInterface::class);
        $twitter_service
            ->shouldReceive('getUser')
            ->andThrow(new \Exception());
        $last_tweets = new LastTweets($twitter_service, "pennywise", 10);
        $this->assertTrue(count($last_tweets->getErrors()) > 0);
        $this->assertTrue($last_tweets->hasConnectionProblems());
    }
}
