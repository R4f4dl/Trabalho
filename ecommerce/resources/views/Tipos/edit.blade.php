@extends('layout')

@section('conteudo')

<h1>Alterar Tipo do Produto</h1>
<form method="POST" action="{{ route('Tipo.update', ['tipo' => $Tipo->id]) }}">
    @CSRF
    @METHOD('PUT')

    <a href="{{ route('Tipo.index')}}" class="btn btn-success mb-3">Editar Registro</a>

          <div class="mb-3">
          <label for="Nome" class="form-label">Informe o Tipo do Produto:</label>
          <input type="text" id="Nome" name="Nome" class="form-control" value="{{old('nome', $Tipo->Nome)}}" required="">
      </div>
      <div class="mb-3">
          <label for="Descricao" class="form-label">Descrição (Opcional):</label>
          <input type="text" id="Descricao" name="Descricao" class="form-control" value="{{old('Descricao', $Tipo->Descricao)}}" >
      </div>
      <button type="submit" class="btn btn-primary">Salvar</button>
  </form>


@endsection