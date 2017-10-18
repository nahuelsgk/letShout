<?php

namespace AppBundle\Controller;

use AppBundle\Domain\LastTweets;
use AppBundle\Services\TwitterService;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController extends FOSRestController
{
    /**
     * @Route("/api/{screen_name}/{count}")
     * @param Request $request
     * @param string  $screen_name
     * @param int     $count
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param string $screen_name
     */
    public function getLastNTwitterCacheAction(Request $request, $screen_name, $count)
    {
        $use_cache = filter_var($request->query->get('cache', true), FILTER_VALIDATE_BOOLEAN);

        $cache = new FilesystemCache();
        $key   = $this->buildCacheKey($screen_name, $count);

        if ($cache->has($key) && $use_cache === true) {
            $tweets = $cache->get($key);
        } else {
            $twitter_service = new TwitterService(
                $this->container->getParameter('oauth_access_token'),
                $this->container->getParameter('oauth_access_token_secret'),
                $this->container->getParameter('consumer_key'),
                $this->container->getParameter('consumer_secret')
            );

            $last_tweets = new LastTweets($twitter_service, $screen_name, $count);

            if ($last_tweets->userNotFound()) {
                return new JsonResponse(['errors' => 'User not found'], 404);
            }

            $tweets = $last_tweets->getTweets();
            $cache->set($key, $tweets, 5 * 60);
        }

        return new JsonResponse($tweets);
    }

    /**
     * @param $screen_name
     * @param $count
     *
     * @return string
     */
    private function buildCacheKey($screen_name, $count)
    {
        return 'last_tweets_' . $screen_name . '_' . $count;
    }
}
