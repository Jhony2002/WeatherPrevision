<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'cidades';
    public $timestamps = false;
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'longitude',
        'latitude',
        'temperatura',
        'sensacao_termica',
        'temperatura_maxima',
        'temperatura_minima',
        'pressao_termica',
        'umidade',
        'porcentagem_de_nuvem'
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
    ];

    /**
     * Os atributos que devem ser ocultados para arrays.
     *
     * @var array
     */
    protected $hidden = [];

    // Outros métodos, relacionamentos, validações, etc., podem ser adicionados aqui.

}
