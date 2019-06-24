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
        $return->action     = true;
        
        $deputado         = new Deputado();
        $deputadosList    = $deputado->all();
        
        VerbaIndenizatoria::query()->delete(); // Limpa tabela de verbas

        foreach($deputadosList as $deputado)
        {
            $returnVerbas = DeputadoRepository::updateVerbasIndenizatorias($deputado);            

            if (isset($returnVerbas->action) && $returnVerbas->action == false)
            {
                $return->action = false;
                $return = $returnVerbas;
            }
        }

        if ($return->action == true && VerbaIndenizatoria::all()->count() > 0)
        {
            // Possui verbas no banco, então calcula o top 5
            $top5               = [];
            $meses              = ["Jan" => 1, "Fev" => 2, "Mar" => 3, "Abr" => 4, "Mai" => 5, "Jun" => 6, "Jul" => 7,
                                    "Ago" => 8, "Set" => 9, "Out" => 10, "Nov" => 11, "Dez" => 12];
            $verbas             = VerbaIndenizatoria::all();
            $return->message    = 'Lista processada com sucesso!';
            foreach($meses as $mes => $numeroMes)
            {
                $top5[$mes] = DB::table('deputados AS d')
                            ->select('d.name AS deputado', DB::raw('COUNT(d.id) AS qtdVerbasPorTipo'), 'd.partido AS partido')
                            ->join('verbas_indenizatorias AS vi', 'd.id','vi.deputado_id')
                            ->where('vi.month','=',$numeroMes)
                            ->groupBy('d.id')
                            ->orderBy('qtdVerbasPorTipo','DESC')
                            ->limit(5)
                            ->get();
            }
            $return->top5 = $top5;
        }
        return response()->json($return);
    }
}