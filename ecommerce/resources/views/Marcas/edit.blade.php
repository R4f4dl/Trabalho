@extends('layout')

@section('conteudo')

<h1>Alterar cliente</h1>
<form method="POST" action="{{ route('Marca.update', ['Marca' => $Marca->id]) }}">
    @CSRF
    @METHOD('PUT')

    <a href="{{ route('Marca.index')}}" class="btn btn-success mb-3">Esitar Registro</a>

          <div class="mb-3">
          <label for="Nome" class="form-label">Informe o Nome da Marca:</label>
          <input type="text" id="Nome" name="Nome" class="form-control" value="{{old('nome', $Marca->Nome)}}" required="">
      </div>
      <button type="submit" class="btn btn-primary">Salvar</button>
  </form>


@endsection