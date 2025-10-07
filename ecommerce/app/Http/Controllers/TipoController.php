<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipo;
use Illuminate\Support\Facades\Log;

class TipoController 
{
    public readonly Tipo $tipo;

    public function __construct()
    {
        $this->tipo = new Tipo();
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = $this->tipo->all();
        return view('Tipos.Tipo',['tipos'=> $tipos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = Tipo::all();
        return view("Tipos.TipoCreate", compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Tipo::create($request->all());
            return redirect()->route("Tipo.index")
                    ->with("sucesso", "Registro inserido!");
        } catch(\Exception $e){
            Log::info("Erro ao salvar o registro do Tipo do Produto! ".$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->route("Tipo.index")
                    ->with("erro", "Erro ao inserir!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipo $tipo)
    {
        return view('Tipos.show',['Tipo' => $tipo]);        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tipo = Tipo::findOrFail($id);
        return view('Tipos.edit',['Tipo' => $tipo]);           
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tipo)
    {
        $data = [
            'Nome' => $request -> Nome,
            'Descricao' => $request -> Descricao
        ];

        Tipo::where('id',$tipo) -> update($data);
        return redirect()->route("Tipo.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipo = Tipo::findOrFail($id);

        // Verifica se existem produtos vinculados
        if ($tipo->produtos()->count() > 0) {
            return redirect()->route('Tipo.index')
                ->with('error', 'Não é possível deletar o Tipo, pois ela está sendo utilizada por produtos.');
        }

        // Se não houver produtos, deleta a marca
        $tipo->delete();

        return redirect()->route('Tipo.index')->with('success', 'Tipo excluído com sucesso!');
    }

}
