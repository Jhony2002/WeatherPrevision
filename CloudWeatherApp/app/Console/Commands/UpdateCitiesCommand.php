<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cidade;
use App\Jobs\UpdateCityWeather;

class UpdateCitiesCommand extends Command
{
    protected $signature = 'update:cities';
    protected $description = 'Atualiza as cidades';

    public function handle(): void
    {
        Cidade::all()
            ->each(fn($cidade) => UpdateCityWeather::dispatch($cidade));

        $this->info('Cidades enviadas para atualização.');
    }
}
