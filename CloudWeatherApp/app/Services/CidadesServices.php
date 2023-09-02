<?php

namespace App\Services;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use GuzzleHttp\Client;

class CidadesServices
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function fetchDataFromApiMapQuest($url, $key,$local)
    {
        try {
            $client = new Client();
            $response = $client->get($url, [
                'query' => [
                    'key' => $key,
                    'location' => $local,
                ],
            ]);

           return json_decode($response->getBody()->getContents(),true);
        } catch (\Exception $e) {
            throw new \Exception('Erro na requisição à API: ' . $e->getMessage());
        }
    }
    public function fetchDataFromWeather($url,$location,$auth)
    {
        try {
            $client = new Client();
            $response = $client->get($url, [
                'query' => [
                    'lat' => $location['latitude'],
                    'lon' => $location['longitude'],
                    'appid' => $auth
                ],
            ]);
           return json_decode($response->getBody()->getContents(),true);
        } catch (\Exception $e) {
            throw new \Exception('Erro na requisição à API: ' . $e->getMessage());
        }
    }
}
