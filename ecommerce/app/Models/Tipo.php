<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    // Nome da tabela no banco
    protected $table = 'tipo';

    // Define que a chave primária é auto-increment
    public $incrementing = true;

    // Campos que podem ser preenchidos em massa
    protected $fillable = ['Nome', 'Descricao', 'id'];

    /**
     * Relação: um tipo tem muitos produtos
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produtos()
    {
        // 'id_tipo' é a coluna na tabela Produtos que referencia o Tipo
        return $this->hasMany(Produto::class, 'id_tipo', 'id');
    }
}