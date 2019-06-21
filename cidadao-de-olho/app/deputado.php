<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class deputado extends Model
{
    protected $table    = 'deputado';
    protected $guarded  = ['id'];

    protected $fillable = ['id_almg', 'name', 'partido', 'tag_localizacao'];
}
