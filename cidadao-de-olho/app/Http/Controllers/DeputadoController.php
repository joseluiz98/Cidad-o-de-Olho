<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DeputadoRepository;
use App\Repositories\VerbasIndenizatoriasRepository;
use App\Deputado;

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
            $returnObject->body   = $res;
        }
        else
        {
            $returnObject->code   = 500;
            $returnObject->msg    = "Não foi possível recuperar a lista de deputados!";
        }        

        return response()->json($returnObject);
    }

    public function index() {
        return Deputado::all();
    }

    public function topCincoVerbas() {
        $deputadosList = Deputado::all();

        foreach($deputadosList as $deputado)
        {
            $return = DeputadoRepository::updateVerbasIndenizatorias($deputado);

            if (isset($return->action) && !$return->action)
            {
                return $return->message;
            }
        }
    }
}