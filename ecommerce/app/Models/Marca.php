<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    // A tabela no banco geralmente está em lowercase; alinhar para evitar ambiguidades
    protected $table = 'marcas'; // Nome da tabela no banco
    public $incrementing = true;
    
    protected $fillable = ['Nome'];

    // Relação correta: uma marca tem muitos produtos
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_marca', 'id');
    }
}