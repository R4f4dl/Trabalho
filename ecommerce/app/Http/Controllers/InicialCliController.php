<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;

class InicialCliController extends Controller
{
    public function clindex()
    {
        $pedido = Pedido::where('user_id', Auth::id())
            ->where('status', 'aberto')
            ->with('itens.pedido')
            ->first();

        $produtos = Produto::with('marca')->get(); // se quiser incluir nome da marca

        return view('inicial-cli', compact('pedido', 'produtos'));
    }
}
