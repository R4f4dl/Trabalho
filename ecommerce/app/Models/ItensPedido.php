<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItensPedido extends Model
{
    protected $table = "itens_pedidos";
    public $incrementing = true;

    // Ajustado para o esquema do banco:
    // - coluna que referencia produtos: 'produtos_id'
    // - coluna de preço na tabela é 'PRECO' (maiúsculo)
    protected $fillable = ['quantidade', 'produtos_id', 'pedido_id', 'preco'];

    public function pedido(){
        return $this->belongsTo(Pedido::class, 'pedido_id', 'id');
    }

    public function produto(){
        // chave estrangeira no banco é 'produtos_id' que referencia 'produtos.id'
        return $this->belongsTo(Produto::class, 'produtos_id', 'id');
    }

    // A tabela usa 'PRECO' como nome da coluna; mapeamos para o atributo 'preco'
    public function getPrecoAttribute()
    {
        return $this->attributes['PRECO'] ?? null;
    }

    public function setPrecoAttribute($value)
    {
        $this->attributes['PRECO'] = $value;
    }
}
