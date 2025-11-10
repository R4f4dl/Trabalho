<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Marca;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InicialCliController extends Controller
{
    public function clindex(Request $request)
    {
        $pedido = Pedido::where('user_id', Auth::id())
            ->where('status', 'aberto')
            ->with('itens.pedido')
            ->first();

        $query = Produto::with('marca', 'tipo');
        if ($request->filled('tamanho')) {
            $query->where('Tamanho', $request->tamanho);
        }
        if ($request->filled('cor')) {
            $query->where('Cor', $request->cor);
        }
        if ($request->filled('genero')) {
            $query->where('Genero', $request->genero);
        }
        if ($request->filled('marca')) {
            $m = $request->marca;
            if (is_numeric($m)) {
                $query->where('id_marca', $m);
            } else {
                $query->whereHas('marca', function($q) use ($m) {
                    $q->where('Nome', 'like', "%{$m}%");
                });
            }
        }
        if ($request->filled('tipo')) {
            $t = $request->tipo;
            if (is_numeric($t)) {
                $query->where('id_tipo', $t);
            } else {
                $query->whereHas('tipo', function($q) use ($t) {
                    $q->where('Nome', 'like', "%{$t}%");
                });
            }
        }
        if ($request->filled('preco_min')) {
            $query->where('Valor', '>=', (float) $request->preco_min);
        }
        if ($request->filled('preco_max')) {
            $query->where('Valor', '<=', (float) $request->preco_max);
        }
        if ($request->filled('base_valor')) {
            $bv = $request->base_valor;
            if (strpos($bv, '+') !== false) {
                $min = (float) rtrim($bv, '+');
                $query->where('Valor', '>=', $min);
            } elseif (strpos($bv, '-') !== false) {
                [$min, $max] = explode('-', $bv);
                $query->whereBetween('Valor', [(float)$min, (float)$max]);
            }
        }

        $produtos = $query->get();

        $marcas = Marca::all();
        $tipos = Tipo::all();
        $tamanhos = Produto::select('Tamanho')
            ->whereNotNull('Tamanho')
            ->distinct()
            ->pluck('Tamanho');

        $cores = Produto::select('Cor')
            ->whereNotNull('Cor')
            ->distinct()
            ->pluck('Cor');

        return view('inicial-cli', compact('pedido', 'produtos', 'marcas', 'tipos', 'tamanhos', 'cores'));
    }
}
