<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {

    protected $table = "clientes";
    public $incrementing = true;

    protected $fillable = ['nome', 'email', 'categoria_id'];

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }


}