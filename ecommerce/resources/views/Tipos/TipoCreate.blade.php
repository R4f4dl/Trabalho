@extends('layout')

@section('conteudo')

  <h2>Novo Tipo de Produtos</h2>
  <form method="post" action="/Tipo">
      @CSRF
      <div class="mb-3">
          <label for="Nome" class="form-label">Informe o Tipo do Produto:</label>
          <input type="text" id="Nome" name="Nome" class="form-control" required="">
      </div>
      <div class="mb-3">
          <label for="Descricao" class="form-label">Descrição (Opcional):</label>
          <input type="text" id="Descricao" name="Descricao" class="form-control" >
      </div>
      <button type="submit" class="btn btn-primary">Enviar</button>
  </form>


@endsection