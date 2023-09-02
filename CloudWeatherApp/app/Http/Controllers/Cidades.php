<?php

namespace App\Http\Controllers;

use App\Services\CidadesServices;
use Illuminate\Http\Request;

class Cidades extends Controller
{
    private $service;
    const MAPQUESTURL = "http://www.mapquestapi.com/geocoding/v1/address";
    const APIAUTENTICATION = "meYSvRpLBvZUZOUVEiDkyCYbup6W283Z";
    const OPENWEATHER = "http://api.openweathermap.org/data/2.5/weather";
    const APIAUTENTICATION2 = "1a2bab3be5ad6d6a6a97031631e2f2e8";

    public function __construct(CidadesServices $service)
    {
        $this->service = $service;
    }

    public function getMapQuest(Request $request)
    {
        $coordenates = array();
        $result = $this->service->fetchDataFromApiMapQuest(self::MAPQUESTURL, self::APIAUTENTICATION, $request->city);
        $coordenates['latitude'] = $result['results'][0]['locations'][0]['latLng']['lat'];
        $coordenates['longitude'] = $result['results'][0]['locations'][0]['latLng']['lng'];

        $response = $this->service->fetchDataFromWeather(self::OPENWEATHER, $coordenates, self::APIAUTENTICATION2);

        return $this->formatJson($response);
    }

    public function formatJson($informations)
    {
        $previsions = array();

        $previsions = array(
            "temperatura" => $informations['main']["temp"],
            "sensaçãoTermica" => $informations['main']["feels_like"],
            "tempoMinimo" => $informations['main']["temp_min"],
            "tempoMaximo" => $informations['main']["temp_max"],
            "pressao" => $informations['main']["pressure"],
            "umidade" => $informations['main']["humidity"],
            "nivelDoMar" => $informations['main']["sea_level"],
            "porcentagemDeNuvem" => $informations['clouds']["all"],
            "cidade" => $informations['name']
        );
        return $previsions;
    }
}
