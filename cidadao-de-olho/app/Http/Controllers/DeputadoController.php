<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Repositories\DeputadoRepository;
use App\Repositories\VerbasIndenizatoriasRepository;
use App\Models\Deputado;
use App\Models\VerbaIndenizatoria;

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
        
        $return             = new \stdClass();
        $return->message    = "Não foram encontradas verbas indenizatórias para este período";
        $return->action     = false;
        
        $deputado         = new Deputado();
        $deputadosList    = $deputado->all();
        
        VerbaIndenizatoria::query()->delete(); // Limpa tabela de verbas

        foreach($deputadosList as $deputado)
        {
            $returnVerbas = DeputadoRepository::updateVerbasIndenizatorias($deputado);            

            if (isset($returnVerbas->action) && !$returnVerbas->action)
            {
                $return = $returnVerbas;
            }
        }

        if (VerbaIndenizatoria::all()->count() > 0)
        {
            // Possui verbas no banco, então calcula o top 5
            $top5     = [];
            $meses    = [1,2,3,4,5,6,7,8,9,10,11,12];
            $verbas   = VerbaIndenizatoria::all();
            foreach($meses as $mes)
            {
                $top5[] = DB::table('deputados AS d')
                            ->select('d.id AS deputado', DB::raw('COUNT(d.id) AS contador'))
                            ->join('verbas_indenizatorias AS vi', 'd.id','vi.deputado_id')
                            ->where('vi.month','=',$mes)
                            ->groupBy('d.id')
                            ->orderBy('contador','DESC')
                            ->limit(5)
                            ->get();
            }
        }
        dd($top5);
        return $return;
    }
}