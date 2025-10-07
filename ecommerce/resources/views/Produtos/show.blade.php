@extends('layout')

@section('conteudo')

<h1>Produtos Cadastrados</h1>

{{-- Formulário de exclusão --}}
<form method="POST" action="{{ route('Produto.destroy', $Produto->id) }}">
    @csrf
    @method('DELETE')

    {{-- Botão para criar novo registro --}}
    <a href="{{ route('Produto.index') }}" class="btn btn-success mb-3">Novo Registro</a>

    <div class="mb-3">
        <label class="form-label">ID:</label>
        <input disabled value="{{ $Produto->id }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Imagem:</label>
        <input disabled value="{{ $Produto->imagem ?? '—' }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Tamanho:</label>
        <input disabled value="{{ $Produto->Tamanho ?? '—' }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Cor:</label>
        <input disabled value="{{ $Produto->Cor ?? '—' }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Gênero:</label>
        <input disabled value="{{ $Produto->Genero ?? '—' }}" type="text" class="form-control">
    </div>

    {{-- Marca --}}
    <div class="mb-3">
        <label class="form-label">Marca:</label>
        <input disabled value="{{ $Produto->Marca->Nome ?? '—' }}" type="text" class="form-control">
    </div>

    {{-- Tipo --}}
    <div class="mb-3">
        <label class="form-label">Tipo:</label>
        <input disabled value="{{ $Produto->Tipo->Nome ?? '—' }}" type="text" class="form-control">
    </div>

    {{-- Ações --}}
    <p>Deseja excluir esse registro?</p>
    <button type="submit" class="btn btn-danger">Sim</button>
    <a href="#" class="btn btn-secondary" onClick="history.back()">Não</a>

</form>

@endsection
