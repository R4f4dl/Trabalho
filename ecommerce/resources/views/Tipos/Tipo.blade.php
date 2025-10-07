@extends('layout')

@section('conteudo')

          <h2>Tipos de Produtos</h2>

          @if(session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif

          @if(session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif


          <a href="/Tipo/create" class="btn btn-success mb-3">Novo Registro</a>
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th><th>Descrição</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              
                @foreach ($tipos as $tipo)
                <tr>
                  <td>{{$tipo -> id}}</td>
                  <td>{{$tipo -> Nome}}</td>
                  <td>{{$tipo -> Descricao}}</td>
                  <td class="d-flex gap-2">
                    <a href="{{ route('Tipo.edit', $tipo) }}" class="btn btn-sm btn-warning">Editar</a>
                    <a href="{{ route('Tipo.show', $tipo) }}" class="btn btn-sm btn-info">Consultar</a>
                  </td>
                </tr>
                @endforeach

              
            </tbody>
          </table>

@endsection

