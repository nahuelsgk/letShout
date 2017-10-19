<?php namespace Tests\AppBundle\Controller;

use AppBundle\Service\TwitterService;
use AppBundle\Service\TwitterServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testApiLastTweets()
    {
        $client = static::createClient();

        /** @var TwitterServiceInterface $twitter_service */
        $twitter_service = \Mockery::mock(TwitterService::class);
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
        $client->getContainer()->set(TwitterService::class, $twitter_service);
        $crawler = $client->request('GET', '/api/nahuelsgk/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(2, json_decode($client->getResponse()->getContent()));
    }
}
