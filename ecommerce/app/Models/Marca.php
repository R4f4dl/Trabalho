<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'Marcas'; // Nome da tabela no banco
    public $incrementing = true;
    
    protected $fillable = ['Nome'];

    // Relação correta: uma marca tem muitos produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_marca', 'id');
    }
}