<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Support\Facades\Log;

class MarcaController 
{
    public readonly Marca $marca;

    public function __construct()
    {
        $this->marca = new Marca();
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Marcas = $this->marca->all();
        return view('Marcas.Marca',['marcas'=> $Marcas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Marcas = Marca::all();
        return view("Marcas.MarcaCreate", compact('Marcas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Marca::create($request->all());
            return redirect()->route("Marca.index")
                    ->with("sucesso", "Registro inserido!");
        } catch(\Exception $e){
            Log::info("Erro ao salvar o registro da Marca! ".$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->route("Marca.index")
                    ->with("erro", "Erro ao inserir!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marcas)
    {
        return view('Marcas.show',['Marca' => $marcas]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Marca = Marca::findOrFail($id);
        return view('Marcas.edit',['Marca' => $Marca]);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $Marca)
    {
            $data = [
            'Nome' => $request -> Nome,
        ];

        Marca::where('id',$Marca) -> update($data);
        return redirect()->route("Marca.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);

        // Verifica se existem produtos vinculados
        if ($marca->produtos()->count() > 0) {
            return redirect()->route('Marca.index')
                ->with('error', 'Não é possível deletar a marca, pois ela está sendo utilizada por produtos.');
        }

        // Se não houver produtos, deleta a marca
        $marca->delete();

        return redirect()->route('Marca.index')->with('success', 'Marca excluída com sucesso!');
    }

}
