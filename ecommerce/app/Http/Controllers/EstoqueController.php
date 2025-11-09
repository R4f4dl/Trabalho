<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Support\Facades\Log;

class EstoqueController
{
    public readonly Estoque $estoque;

    public function __construct()
    {
        $this->estoque = new Estoque();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estoques = $this->estoque->with('produto')->get();
        return view('Estoque.Estoque', ['estoques' => $estoques]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produtos = Produto::with(['marca', 'tipo'])->orderBy('Cor')->get();
        return view('Estoque.EstoqueCreate', compact('produtos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produtos' => 'required|exists:produtos,id',
            'PrecoCompra' => 'required|integer|min:0',
            'PrecoVenda' => 'required|integer|min:0',
            'Quantidade' => 'required|integer|min:1',
        ]);

        try {
            Estoque::create($request->all());
            return redirect()->route("Estoque.index")
                    ->with("sucesso", "Estoque registrado com sucesso!");
        } catch(\Exception $e){
            Log::info("Erro ao salvar o estoque! ".$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->route("Estoque.index")
                    ->with("erro", "Erro ao registrar estoque!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Estoque $estoque)
    {
        return view('Estoque.show', ['estoque' => $estoque]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $estoque = Estoque::findOrFail($id);
        $produtos = Produto::orderBy('Cor')->get();
        return view('Estoque.Edit', compact('estoque', 'produtos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = [
            'id_produtos' => $request->id_produtos,
            'PrecoCompra' => $request->PrecoCompra,
            'PrecoVenda' => $request->PrecoVenda,
            'Quantidade' => $request->Quantidade,
        ];

        Estoque::where('id', $id)->update($data);
        return redirect()->route("Estoque.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estoque = Estoque::findOrFail($id);
        $estoque->delete();

        return redirect()->route('Estoque.index')->with('success', 'Entrada de estoque excluída com sucesso!');
    }
}
