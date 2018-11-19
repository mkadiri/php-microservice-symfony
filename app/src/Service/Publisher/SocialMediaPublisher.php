<?php

namespace App\Service\Publisher;

use App\Entity\AdEntity;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class SocialMediaPublisher
{
    const URI = 'http://httpbin.org/get';

    /**
     * @var SocialMediaHttpClient
     */
    private $socialMediaHttpClient;

    public function __construct(SocialMediaHttpClient $socialMediaHttpClient)
    {
        $this->socialMediaHttpClient = $socialMediaHttpClient;
    }

    /**
     * @param AdEntity $adEntity
     * @return PromiseInterface
     */
    public function publish(AdEntity $adEntity): PromiseInterface
    {
        // TODO: REMOVE MOCK
        // this is just an example function, I've used a GET instead of a POST to get something working
        return $this->socialMediaHttpClient->get()->getAsync(self::URI);
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    public function hasPublished(ResponseInterface $response)
    {
        // TODO: REMOVE MOCK
        // you'll need to check the response and decide what should be returned
        return true;
    }
}