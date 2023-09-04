<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cidade; // Certifique-se de importar o modelo Cidade e outras classes relevantes
use App\Services\CidadesServices;

class UpdateCitiesCommand extends Command
{
    const MAPQUESTURL = "http://www.mapquestapi.com/geocoding/v1/address";
    const APIAUTENTICATION = "meYSvRpLBvZUZOUVEiDkyCYbup6W283Z";
    const OPENWEATHER = "http://api.openweathermap.org/data/2.5/weather";
    const APIAUTENTICATION2 = "1a2bab3be5ad6d6a6a97031631e2f2e8";

    protected $signature = 'update:cities';
    protected $description = 'Atualiza as cidades';
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new CidadesServices;
    }

    public function handle()
    {
        $this->updateCities();
    }

    private function updateCities()
    {
        $cidades = Cidade::select('longitude', 'latitude')->get();

        foreach ($cidades as $cidade) {
           $weatherInformations= $this->service->getPrevisionsFromWeather(self::OPENWEATHER, $cidade, self::APIAUTENTICATION2);
           $this->service->formatJson($weatherInformations);
        }

        $this->info('Cidades atualizadas com sucesso.');
    }
}
