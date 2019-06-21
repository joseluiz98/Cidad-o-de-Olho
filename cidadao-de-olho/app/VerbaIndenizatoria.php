<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerbaIndenizatoria extends Model
{
    protected $table    = 'verbas_indenizatorias';
    protected $guarded  = ['id'];

    protected $fillable = ['deputado_id','month','value', 'type'];
}
