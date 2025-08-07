<?php

namespace App\Jobs;

use App\Models\Cidade;
use App\Services\CidadesServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCityWeather implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Cidade $cidade;

    public function __construct(Cidade $cidade)
    {
        $this->cidade = $cidade;
    }

    public function handle(CidadesServices $service): void
    {
        $location = [
            'latitude' => $this->cidade->latitude,
            'longitude' => $this->cidade->longitude,
        ];

        $weather = $service->getPrevisionsFromWeather(
            CidadesServices::OPENWEATHER,
            $location,
            CidadesServices::APIAUTENTICATION2
        );

        $service->formatJson($weather);
    }
}
