<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VerbaIndenizatoria;

class Deputado extends Model
{
    protected $table    = 'deputados';
    protected $guarded  = ['id'];

    protected $fillable = ['id_almg', 'name', 'partido', 'tag_localizacao'];

    public function verbasIndenizatorias()
    {
        return $this->hasMany('App\Models\VerbaIndenizatoria', 'deputado_id');
    }

}