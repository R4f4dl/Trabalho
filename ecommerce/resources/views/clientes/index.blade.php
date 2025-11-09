@extends('layout')

@section('conteudo')

<h2>Clientes</h2>
    @if(session('sucesso'))
        <p class="text-success">{{ session('sucesso') }}</p>
    @endif
    @if(session('erro'))
        <p class="text-danger">{{ session('erro') }}</p>
    @endif
    <a href="/clientes/create" class="btn btn-success mb-3">Novo Registro</a>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->nome }}</td>
                <td>{{ $c->email }}</td>
                <td class="d-flex gap-2">
                    <a href="/clientes/{{ $c->id }}/edit" class="btn btn-sm btn-warning">Editar</a>
                    <a href="/clientes/{{ $c->id }}" class="btn btn-sm btn-info">Consultar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>    

@endsection