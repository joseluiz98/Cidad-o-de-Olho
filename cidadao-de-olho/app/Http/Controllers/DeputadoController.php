<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DeputadoRepository;

class DeputadoController extends Controller
{
    public function initialize()
    {
        $deputadoRepository = new DeputadoRepository();
        $returnObject   = new \stdClass();
        $res            = $deputadoRepository->getDeputadosList();
        if (isset($res) && !empty($res))
        {
            $deputadoRepository->updateDeputadosList($res);
            $returnObject->code   = 200;
            $returnObject->body   = json_decode($res)->list;
        }
        else
        {
            $returnObject->code   = 500;
            $returnObject->msg    = "Não foi possível recuperar a lista de deputados!";
        }        

        return response()->json($returnObject);
    }
}