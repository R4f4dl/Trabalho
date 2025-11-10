<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Marca;
use App\Models\Tipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\ItensPedido;

class CarrinhoController extends Controller
{
    public function mostrarProdutos(Request $request){
        $query = Produto::with('marca', 'tipo');

        // filtros simples por igualdade
        if ($request->filled('tamanho')) {
            $query->where('Tamanho', $request->tamanho);
        }
        if ($request->filled('cor')) {
            $query->where('Cor', $request->cor);
        }
        if ($request->filled('genero')) {
            $query->where('Genero', $request->genero);
        }

        // marca: aceita id ou nome parcial
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

        // tipo: aceita id ou nome parcial
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

        // preço: suporte para faixa (preco_min / preco_max) ou select base_valor (ex: 0-50, 50-100, 200+)
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

        return view('welcome', compact('produtos', 'marcas', 'tipos', 'tamanhos', 'cores'));
    }

    /**
     * Mostrar a view do carrinho (cart.blade.php)
     */
    public function mostrarCarrinho(Request $request)
    {
        $pedido = Pedido::where('user_id', Auth::id())
                    ->where('status', 'aberto')
                    ->with('itens.produto')
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

        return view('cart', compact('pedido', 'produtos', 'marcas', 'tipos', 'tamanhos', 'cores'));
    }

    /**
     * Esvaziar o carrinho: remover todos os itens do pedido aberto e zerar total
     */
    public function esvaziarCarrinho()
    {
        $pedido = Pedido::where('user_id', Auth::id())
                    ->where('status', 'aberto')
                    ->first();

        if ($pedido) {
            // remover itens relacionados
            ItensPedido::where('pedido_id', $pedido->id)->delete();
            // zerar total
            $pedido->total = 0;
            $pedido->save();
        }

        return redirect('/carrinho');
    }

    public function adicionarCarrinho(int $id){
        $user = Auth::user();
        $produto = Produto::findOrFail($id);
        $pedido = Pedido::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'aberto'
        ], ['total' => 0]);
    // coluna real no banco que referencia produtos é 'produtos_id'
    $item = ItensPedido::where('pedido_id', $pedido->id)
            ->where('produtos_id', $id)->first();
        if($item){
            $item->quantidade += 1;
            $item->save();
        } else {
            ItensPedido::create([
                'pedido_id' => $pedido->id,
                'produtos_id' => $id,
                'quantidade' => 1,
                'preco' => $produto->Valor
            ]);
        }
    // a coluna de preço na tabela chama-se 'PRECO' — usar no cálculo bruto
    $pedido->total = ItensPedido::where('pedido_id', $pedido->id)
                ->sum(DB::raw('quantidade * PRECO'));
        $pedido->save();
        // permanecer na mesma página em que o usuário clicou em "Comprar"
        return redirect()->back();
    }

    public function removerCarrinho($id){
        $pedido = Pedido::where('user_id', Auth::id())
                        ->where('status', 'aberto')
                        ->first();
        $item = ItensPedido::findOrFail($id);
        if($item){
            if ($item->quantidade == 1)
                $item->delete();
            else {
                $item->quantidade -= 1;
                $item->save();
            }
            $pedido->total = ItensPedido::where('pedido_id', $pedido->id)
                            ->sum(DB::raw("quantidade * PRECO"));
            $pedido->save();
        }
        // permanecer na mesma página (normalmente /carrinho)
        return redirect()->back();
    }

    public function fecharPedido(){
        $pedido = Pedido::where('user_id', Auth::id())
                    ->where('status', 'aberto')
                    ->first();
        $pedido->status = "fechado";
        $pedido->save();
        return redirect('inicial-cli');
    }
}
