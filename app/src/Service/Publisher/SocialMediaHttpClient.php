<?php

namespace App\Service\Publisher;

use GuzzleHttp\Client;

class SocialMediaHttpClient
{
    public function get()
    {
        return new Client();
    }
}