<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function clindex()
    {

        $produtos = $this->produto->all();
        return view('inicial-cli', ['produtos' => $produtos]);

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
            // validação de imagens: array opcional com máximo 3 arquivos de imagem
            'imagens' => 'nullable|array|max:3',
            'imagens.*' => 'image|max:4096',
        ]);

        // usamos transação para garantir que tudo seja salvo ou nada
        DB::beginTransaction();

        try {
            // montar os dados base do produto (sem imagem por enquanto)
            $data = $request->only(['Tamanho', 'Cor', 'Genero','Valor', 'id_marca', 'id_tipo']);

            // processar uploads de imagens (até 3)
            $urls = null;
            $uploaded = $request->file('imagens');
            if ($uploaded && is_array($uploaded)) {
                $urls = [];
                $count = 0;
                foreach ($uploaded as $file) {
                    if (!$file) continue;
                    // garante no máximo 3
                    if ($count >= 3) break;
                    $path = $file->store('produtos', 'public');
                    // url pública
                    $urls[] = Storage::url($path);
                    $count++;
                }
            }

            if ($urls !== null) {
                $data['imagem'] = $urls; // será salvo como JSON (cast no model)
            }

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
        // ordena por nome (uso de caixa (case) pode variar no schema); usar 'nome' por compatibilidade
        $marcas = Marca::orderBy('nome')->get();
        $tipos = Tipo::orderBy('nome')->get();
        return view('Produtos.edit', compact('Produto','marcas','tipos'));       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $Produto)
    {
        $request->validate([
            'Tamanho' => 'required|string',
            'Cor' => 'required|string|max:400',
            'Genero' => 'required|string',
            'Valor' => 'required|numeric|min:0',
            'id_marca' => 'required|exists:marcas,id',
            'id_tipo' => 'required|exists:tipo,id',
            'imagens' => 'nullable|array|max:3',
            'imagens.*' => 'image|max:4096',
            // arquivos para substituir imagens específicas
            'imagens_replace' => 'nullable|array',
            'imagens_replace.*' => 'image|max:4096',
        ]);

        $data = [
            'Tamanho' => $request->Tamanho,
            'Cor' => $request->Cor,
            'Genero' => $request->Genero,
            'Valor' => $request->Valor,
            'id_marca' => $request->id_marca,
            'id_tipo' => $request->id_tipo,
        ];

        // use Eloquent model instance so casts (imagem => array/json) are applied
        $produto = Produto::findOrFail($Produto);

        DB::beginTransaction();
        try {
            // processa substituições individuais primeiro (se houver)
            $existing = is_array($produto->imagem) ? $produto->imagem : ($produto->imagem ? [$produto->imagem] : []);

            $replacements = $request->file('imagens_replace');
            if ($replacements && is_array($replacements)) {
                foreach ($replacements as $index => $file) {
                    if (!$file) continue;
                    // armazena e substitui na posição correspondente
                    $path = $file->store('produtos', 'public');
                    $url = Storage::url($path);
                    // se a posição existe, substitui; se não, adiciona até preencher
                    if (isset($existing[$index])) {
                        $existing[$index] = $url;
                    } else {
                        $existing[] = $url;
                    }
                }
            }

            // então processa novos uploads (append) respeitando o limite total de 3
            $uploaded = $request->file('imagens');
            if ($uploaded && is_array($uploaded)) {
                foreach ($uploaded as $file) {
                    if (!$file) continue;
                    if (count($existing) >= 3) break;
                    $path = $file->store('produtos', 'public');
                    $existing[] = Storage::url($path);
                }
            }

            // aplica a lista final (respeitando máximo de 3)
            if (!empty($existing)) {
                $produto->imagem = array_values(array_slice($existing, 0, 3));
            } else {
                // se não houver imagens, garante null
                $produto->imagem = null;
            }

            // aplica demais campos e salva
            $produto->fill($data);
            $produto->save();

            DB::commit();

            // redireciona para /produto (path em minúsculas) conforme solicitado
            return redirect('/produto')->with('success', 'Produto atualizado com sucesso');
        } catch (\Exception $e) {
            DB::rollBack();
            // loga o erro e retorna com mensagem amigável
            \Log::error('Erro ao atualizar produto ID ' . $Produto . ': ' . $e->getMessage());
            return back()->withErrors(['erro' => 'Erro ao salvar: ' . $e->getMessage()])->withInput();
        }
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

    /**
     * Remove a single image from the produto by index.
     * Expects POST with 'index' integer.
     */
    public function removeImage($id, Request $request)
    {
        $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $produto = Produto::findOrFail($id);

        $index = (int) $request->index;

        $images = is_array($produto->imagem) ? $produto->imagem : ($produto->imagem ? [$produto->imagem] : []);

        if (!isset($images[$index])) {
            return back()->withErrors(['erro' => 'Índice de imagem inválido']);
        }

        $url = $images[$index];
        // remove o arquivo do storage se possível (assume Storage::url was used)
        if (!empty($url) && strpos($url, '/storage/') !== false) {
            $relative = ltrim(str_replace('/storage/', '', $url), '/');
            try {
                Storage::disk('public')->delete($relative);
            } catch (\Exception $e) {
                // log e continua (não falha a operação só por não conseguir apagar o arquivo)
                \Log::warning('Não foi possível apagar arquivo de imagem: ' . $e->getMessage());
            }
        }

        // remover do array
        unset($images[$index]);
        $images = array_values($images);
        $produto->imagem = count($images) ? $images : null;
        $produto->save();

        return back()->with('success', 'Imagem removida com sucesso');
    }
}
