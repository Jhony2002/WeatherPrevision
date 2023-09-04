<?php

namespace App\Services;

// use Illuminate\Support\Facades\Gate;
date_default_timezone_set('America/Sao_Paulo');
use App\Models\Cidade;
use GuzzleHttp\Client;

class CidadesServices
{
    protected $client;
    const MAPQUESTURL = "http://www.mapquestapi.com/geocoding/v1/address";
    const APIAUTENTICATION = "meYSvRpLBvZUZOUVEiDkyCYbup6W283Z";
    const OPENWEATHER = "http://api.openweathermap.org/data/2.5/weather";
    const APIAUTENTICATION2 = "1a2bab3be5ad6d6a6a97031631e2f2e8";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getPrevision($request)
    {
        $coordenates = array();
        $result = self::getCoordinatesInformation(self::MAPQUESTURL, self::APIAUTENTICATION, $request->nome);
        $coordenates['latitude'] = $result['results'][0]['locations'][0]['latLng']['lat'];
        $coordenates['longitude'] = $result['results'][0]['locations'][0]['latLng']['lng'];

        $cityAndPrevisionsInfo = self::getPrevisionsFromWeather(self::OPENWEATHER, $coordenates, self::APIAUTENTICATION2);

       // array_merge($cityAndPrevisionsInfo,$coordenates);

        return $this->formatJson($cityAndPrevisionsInfo);
    }

    public function getCoordinatesInformation($url, $key,$local)
    {
        try {
            $response = $this->client->get($url, [
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

    public function getPrevisionsFromWeather($url,$location,$auth)
    {

        try {
            $response = $this->client->get($url, [
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

    public function formatJson($informations)
    {

        $previsions = array(
            "longitude"=> $informations['coord']['lon'],
            "latitude"=> $informations['coord']['lat'],
            "temperatura" => $informations['main']["temp"],
            "sensacaoTermica" => $informations['main']["feels_like"],
            "tempoMinimo" => $informations['main']["temp_min"],
            "tempoMaximo" => $informations['main']["temp_max"],
            "pressao" => $informations['main']["pressure"],
            "umidade" => $informations['main']["humidity"],
            "porcentagemDeNuvem" => $informations['clouds']["all"],
            "cidade" => $informations['name'],
            "ultimaAtualizacao"=>date("Y-m-d H:i:s")
        );

        $this->storeCity($previsions);
        return $previsions;
    }

    public function storeCity($city){

        $registro = Cidade::firstornew(['nome'=>$city['cidade']]);
        $registro->nome = $city['cidade'];
        $registro->longitude = $city['longitude'];
        $registro->latitude = $city['latitude'];
        $registro->temperatura = $city['temperatura'];
        $registro->sensacao_termica= $city['sensacaoTermica'];
        $registro->temperatura_minima= $city['tempoMinimo'];
        $registro->temperatura_maxima= $city['tempoMaximo'];
        $registro->pressao_termica= $city['pressao'];
        $registro->umidade= $city['umidade'];
        $registro->porcentagem_de_nuvem= $city['umidade'];
        $registro->ultima_atualizacao= $city['ultimaAtualizacao'];
        $registro->save();

    }

   /*  private function updateCities()
    {
        $cidades = Cidade::select('longitude', 'latitude')->get();

        foreach ($cidades as $cidade) {
            self::getPrevisionsFromWeather(self::OPENWEATHER, dd($cidade['latitude']), self::APIAUTENTICATION2);
        }


    } */
}
