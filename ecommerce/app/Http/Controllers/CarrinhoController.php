<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\ItensPedido;

class CarrinhoController extends Controller
{
    public function mostrarProdutos(){
        $produtos = Produto::all();
        return view('welcome', compact('produtos'));
    }

    /**
     * Mostrar a view do carrinho (cart.blade.php)
     */
    public function mostrarCarrinho()
    {
        $pedido = Pedido::where('user_id', Auth::id())
                    ->where('status', 'aberto')
                    ->with('itens.produto')
                    ->first();

        $produtos = Produto::with('marca')->get();

        return view('cart', compact('pedido', 'produtos'));
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
