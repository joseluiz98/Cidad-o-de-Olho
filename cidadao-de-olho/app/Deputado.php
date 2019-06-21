<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deputado extends Model
{
    protected $table    = 'deputados';
    protected $guarded  = ['id'];

    protected $fillable = ['id_almg', 'name', 'partido', 'tag_localizacao'];

    public function verbas()
    {
        return $this->belongsToMany('App\VerbasIndenizatorias')->using('App\RoleVerbasIndenizatorias');
    }
}
