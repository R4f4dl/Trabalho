<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $table = 'estoque'; // nome da tabela no banco

    protected $fillable = [
        'PrecoCompra',
        'PrecoVenda',
        'Quantidade',
        'id_produtos',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produtos');
    }
}
