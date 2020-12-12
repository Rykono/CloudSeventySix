<?php

namespace App\Traits\PetFinder;

use Ixudra\Curl\Facades\Curl;

trait GetAuth
{

    static public function getAuth()
    {
      return Curl::to('https://api.petfinder.com/v2/oauth2/token')
                  ->withData([
                    'grant_type' => 'client_credentials',
                    'client_id' => env('PETFINDER_ID'),
                    'client_secret' => env('PETFINDER_SECRET')
                  ])
                  ->asJson()
                  ->post();
    }
  }
