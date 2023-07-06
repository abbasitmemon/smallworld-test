<?php

namespace App\Services;

class MovieService
{
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getMovies()
    {
        $response = $this->client->request('GET', 'https://swapi.dev/api/films', [
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);

        return $response->getBody();
    }
}
