<?php

namespace App\Http\Controllers;

use App\Services\CidadesServices;
use Illuminate\Http\Request;

class Cidades extends Controller
{
    private $service;

    public function __construct(CidadesServices $service)
    {
        $this->service = $service;
    }

    public function getPrevision(Request $request){

        return $this->service->getPrevision($request);

    }


}
