<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    // Nome da tabela no banco
    protected $table = 'Produtos';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'Tamanho',
        'Cor',
        'Genero',
        'id_marca',
        'id_tipo',
        'Valor',
        'imagem'
    ];

    /**
     * Relação com a Marca
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    /**
     * Relação com o Tipo
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'id_tipo');
    }


    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'id_produtos');
    }

}
