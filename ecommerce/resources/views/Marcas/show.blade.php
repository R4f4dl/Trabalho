@extends('layout')

@section('conteudo')

<h1>Marcas Cadastradas</h1>

{{-- Formulário de exclusão --}}
<form method="POST" action="{{ route('Marca.destroy', $Marca->id) }}">
    @csrf
    @method('DELETE')

    {{-- Botão para criar novo registro --}}
    <a href="{{ route('Marca.index') }}" class="btn btn-success mb-3">Novo Registro</a>

    <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input disabled value="{{ $Marca->Nome }}" type="text" id="nome" name="nome" class="form-control" required>
    </div>

    <p>Deseja excluir esse registro?</p>

    <button type="submit" class="btn btn-danger">Sim</button>
    <a href="#" class="btn btn-secondary" onClick="history.back()">Não</a>
</form>

@endsection