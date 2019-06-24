<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use App\Models\Deputado;
use App\Models\VerbaIndenizatoria;

class VerbasIndenizatoriasRepository
{
    public static function updateVerbasIndenizatorias($deputado) {
        $id_almg    = $deputado->id_almg;
        $ano        = 2017;
        $meses      = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        
        try
        {
            // Reset verbas indenizatorias table            
            if ($deputado->verbasIndenizatorias()->delete() || $deputado->verbasIndenizatorias()->count() == 0);
            {
                foreach($meses as $mes)
                {
                    $verbasList  = file_get_contents('http://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/' . $id_almg . '/' . $ano . '/' . $mes . '?formato=json');
                    $verbasList  = json_decode($verbasList)->list;
                    
                    foreach ($verbasList as $verba)
                    {
                        $createVerbaObject   = new \Illuminate\Http\Request();
                        $createVerbaObject->merge(['deputado_id' => $deputado->id]);
                        $createVerbaObject->merge(['month' => $mes]);
                        $createVerbaObject->merge(['value' => $verba->valor]);
                        $createVerbaObject->merge(['type' => $verba->descTipoDespesa]);
                        
                        $createVerbaObject   = VerbasIndenizatoriasRepository::makeCreate($createVerbaObject);
                        // dd($createVerbaObject);
                        if (isset($createVerbaObject->action) && $createVerbaObject->action == false)
                        {
                            throw new \Exception($createVerbaObject->errors);
                            break;
                        }
                        else
                        {
                            VerbasIndenizatoriasRepository::store($createVerbaObject);
                            sleep(1); // aguarda tempo entre requisições imposto pela api
                        }
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            $objectReturn           = new \stdClass();
            $objectReturn->action   = false;
            $objectReturn->message  = $e->getMessage();
            return $objectReturn;
        }
    }

    private static function store($verba) {
        return $verba->save();
    }

    private static function makeCreate($request) {
        $validator = Validator::make($request->all(), [
            'deputado_id' => 'required|numeric',
            'month' => 'required|numeric',
            'value' => 'required',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            $objectReturn           = new \stdClass();
            $objectReturn->errors   = $validator->errors();
            $objectReturn->action   = false;
            return $objectReturn;
        }
        else
        {
            $data   = $request->all();
            $verba  = new VerbaIndenizatoria;
            $verba->fill($data);
            return $verba;
        }
    }
}