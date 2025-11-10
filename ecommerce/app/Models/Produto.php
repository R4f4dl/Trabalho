<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    // Nome da tabela no banco (usar lowercase para seguir o schema real)
    protected $table = 'produtos';

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

    // Trata o campo imagem como um array (JSON) no modelo
    protected $casts = [
        'imagem' => 'array',
    ];

    // Retorna a primeira imagem para uso nas views (ou null)
    public function getFirstImageAttribute()
    {
        if (is_array($this->imagem) && count($this->imagem) > 0) {
            return $this->imagem[0];
        }

        // se por algum motivo ainda for string (antigos registros), retorna ele
        return $this->imagem ?? null;
    }

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
        // a FK na tabela estoque chama-se 'id_produtos' e pode haver múltiplos registros,
        // então expomos uma relação hasMany para cobrir ambos os casos.
        return $this->hasMany(Estoque::class, 'id_produtos');
    }

}
