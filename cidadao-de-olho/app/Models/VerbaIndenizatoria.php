<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerbaIndenizatoria extends Model
{
    protected $table    = 'verbas_indenizatorias';
    protected $guarded  = ['id'];

    protected $fillable = ['deputado_id','month','value', 'type'];

    public function deputado()
    {
        return $this->belongsTo('App\Models\Deputado');
    }
}
