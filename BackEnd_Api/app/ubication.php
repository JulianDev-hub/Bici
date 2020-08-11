<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ubication extends Model
{
    protected $table = 'ubication';
    protected $primaryKey = 'IdUbication';

    //Relacion
    public function user()
    {
        return $this->belongsTo('App\User', 'IdUsuario');
    }

}
