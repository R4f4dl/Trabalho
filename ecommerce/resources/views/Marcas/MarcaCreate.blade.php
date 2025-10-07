@extends('layout')

@section('conteudo')

  <h2>Nova Marca</h2>
  <form method="post" action="/Marca">
      @CSRF
      <div class="mb-3">
          <label for="Nome" class="form-label">Nome da Marca:</label>
          <input type="text" id="Nome" name="Nome" class="form-control" required="">
      </div>

      <button type="submit" class="btn btn-primary">Enviar</button>
  </form>


@endsection