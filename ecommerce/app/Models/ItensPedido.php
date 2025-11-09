<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItensPedido extends Model
{
    protected $table = "itens_pedidos";
    public $incrementing = true;

    protected $fillable = ['quantidade', 'produto_id', 'pedido_id', 'preco'];

    public function pedido(){
        return $this->belongsTo(Pedido::class, 'pedido_id', 'id');
    }

    public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }
}
