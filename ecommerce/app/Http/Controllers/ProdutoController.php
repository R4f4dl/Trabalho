<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use App\Models\Marca;   // <-- importa a Model Marca
use App\Models\Tipo;    // <-- importa a Model Tipo (se também usar)

class ProdutoController 
{
    public readonly Produto $produto;

    public function __construct()
    {
        $this->produto = new Produto();
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = $this->produto->all();
        return view('Produtos.Produto',['produtos'=> $produtos]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
                // pega todas as marcas para popular o <select> de marcas
        $marcas = Marca::orderBy('nome')->get();

        // pega todos os tipos para o <select> de tipos
        $tipos = Tipo::orderBy('nome')->get();


        // retorna a view 'produtos.create' passando as variáveis
        return view('Produtos.ProdutoCreate', compact('marcas', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // validação dos campos do formulário
        $request->validate([
            'Tamanho' => 'required|string', // se for enum, valide com in:PP,P,M,...
            'Cor' => 'required|string|max:400',
            'Genero' => 'required|string',
            'Valor' => 'required|numeric|min:0',
            'id_marca' => 'required|exists:marcas,id',
            'id_tipo' => 'required|exists:tipo,id',
            // validação de imagem (opcional): é um arquivo de imagem e max 2MB
            'imagem' => 'nullable|image|max:16000',
        ]);

        // usamos transação para garantir que tudo seja salvo ou nada
        DB::beginTransaction();

        try {
            // montar os dados base do produto
            $data = $request->only(['Tamanho', 'Cor', 'Genero','Valor', 'id_marca', 'id_tipo','imagem']);

            $produto = Produto::create($data);

            DB::commit();

            // redireciona com mensagem de sucesso
            return redirect()->route('Produto.index')
                ->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            // para desenvolvimento você pode logar ou dar dd($e)
            return back()->withErrors(['erro' => 'Erro ao salvar: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $Produto)
    {
        return view('Produtos.show',['Produto' => $Produto]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Produto = Produto::findOrFail($id);
        $marcas = Marca::orderBy('Nome')->get();
        $tipos = Tipo::orderBy('Nome')->get();
        return view('Produtos.edit', compact('Produto','marcas','tipos'));       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $Produto)
    {
        $data = [
        'Tamanho' => $request->Tamanho,
        'Cor' => $request->Cor,
        'Genero' => $request->Genero,
        'Valor' => $request->Valor,
        'id_marca' => $request->id_marca,
        'id_tipo' => $request->id_tipo,
        'imagem' => $request->imagem,
        ];

        Produto::where('id',$Produto) -> update($data);
        return redirect()->route("Produto.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Busca o produto pelo ID ou retorna 404
        $produto = Produto::findOrFail($id);

        // Se houver alguma lógica para checar dependências, você pode adicionar aqui
        // Exemplo:
        // if ($produto->pedidos()->count() > 0) {
        //     return redirect()->route('Produto.index')
        //         ->with('error', 'Não é possível deletar o produto, pois está vinculado a pedidos.');
        // }

        // Deleta o produto
        $produto->delete();

        // Redireciona de volta para a lista com mensagem de sucesso
        return redirect()->route('Produto.index')
                         ->with('success', 'Produto deletado com sucesso!');
    }
}
