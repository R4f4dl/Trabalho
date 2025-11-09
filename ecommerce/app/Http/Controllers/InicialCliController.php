<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class InicialCliController extends Controller
{
    public function index(){
        $pedido = Pedido::where('user_id', Auth::id())
            ->where('status', 'aberto')
            ->with('itens.pedido')
            ->first();
        return view('inicial-cli', compact('pedido'));
    }
}
