@extends('layout')

@section('conteudo')

    <h2>Marcas</h2>

    {{-- Mensagens de sucesso ou erro --}}
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

    <a href="/Marca/create" class="btn btn-success mb-3">Novo Registro</a>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($marcas as $Marca)
                <tr>
                    <td>{{ $Marca->id }}</td>
                    <td>{{ $Marca->Nome }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('Marca.edit', $Marca) }}" class="btn btn-sm btn-warning">Editar</a>
                        <a href="{{ route('Marca.show', ['marcas' => $Marca]) }}" class="btn btn-sm btn-info">Consultar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
