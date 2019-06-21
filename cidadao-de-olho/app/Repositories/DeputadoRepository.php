<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class DeputadoRepository
{
    public function getDeputadosList() {
        // $client         = new \GuzzleHttp\Client();
        // $res            = $client->get('https://dadosabertos.almg.gov.br/ws/deputados/que_renunciaram?formato=json');
        return $res = json_decode(file_get_contents('http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json'))->list;
    }

    public function updateDeputadosList($request) {
        $this->makeCreateMultiples($request);
    }

    public function store($request) {
        $deputado = new Deputado;
        dd($deputado);
        $deputado->name = 'John';
        $deputado->save();
    }

    private function makeCreateMultiples($request) {
        foreach($request as $deputado) {
            $createDeputadoObject   = new \Illuminate\Http\Request();
            $createDeputadoObject->merge(['id_almg' => $deputado->id]);
            $createDeputadoObject->merge(['name' => $deputado->nome]);
            $createDeputadoObject->merge(['partido' => $deputado->partido]);
            $createDeputadoObject->merge(['tag_localizacao' => $deputado->tagLocalizacao]);
            
            $createDeputadoObject   = $this->makeCreate($createDeputadoObject);            
            dd(createDeputadoObject);
            $this->store($createDeputadoObject);
        }
    }

    private function makeCreate($request) {
        $validator = Validator::make($request->all(), [
            'id_almg' => 'required|numeric',
            'name' => 'required|string',
            'partido' => 'required',
            'tag_localizacao' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            dd('aci');
            return redirect('post/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        else
        {
            return $request;
        }
    }
}