@extends('layout')

@section('conteudo')

<h1>Dados do cliente</h1>
<form method="post" action="/clientes/{{ $cliente->id }}">
    @CSRF
    @METHOD('DELETE')
    <div class="mb-3">
        <label for="nome" class="form-label">Informe o nome:</label>
        <input disabled value="{{$cliente->nome}}" type="text" id="nome" name="nome" class="form-control" required="">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Informe o email:</label>
        <input disabled value="{{$cliente->email}}" type="email" id="email" name="email" class="form-control" required="">
    </div>
    <p>Deseja excluir esse registro?</p>
    <button type="submit" class="btn btn-danger">Sim</button>
    <a href="#" class="btn btn-secondary" onClick="history.back()">
        Não
    </a>
</form>

@endsection