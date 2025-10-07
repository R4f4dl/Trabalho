@extends('layout')

@section('conteudo')

<h1>Tipos de Produtos Cadastrados</h1>

{{-- Formulário de exclusão --}}
<form method="POST" action="{{ route('Tipo.destroy', $Tipo->id) }}">
    @csrf
    @method('DELETE')

    {{-- Botão para criar novo registro --}}
    <a href="{{ route('Tipo.index') }}" class="btn btn-success mb-3">Novo Registro</a>

    <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input disabled value="{{ $Tipo->Nome }}" type="text" id="nome" name="nome" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição:</label>
        <input disabled value="{{ $Tipo->Descricao }}" type="text" id="descricao" name="descricao" class="form-control">
    </div>

    <p>Deseja excluir esse registro?</p>

    <button type="submit" class="btn btn-danger">Sim</button>
    <a href="#" class="btn btn-secondary" onClick="history.back()">Não</a>
</form>

@endsection