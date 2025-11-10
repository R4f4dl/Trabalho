@extends('layout')

@section('conteudo')

<h2>Produtos</h2>

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

<a href="{{ route('Produto.create') }}" class="btn btn-success mb-3">Novo Registro</a>

<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Imagem</th>                
            <th>Tamanho</th>
            <th>Cor</th>
            <th>Genero</th>
            <th>Valor</th>            
            <th>Marca</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produtos as $Produto)
        <tr>
            <td>{{ $Produto->id }}</td>
            <td>
                @if($Produto->first_image)
                    <img src="{{ $Produto->first_image }}" alt="thumb" style="max-width:80px; max-height:60px; object-fit:cover;" />
                @else
                    —
                @endif
            </td>    
            <td>{{ $Produto->Tamanho ?? '—' }}</td>
            <td>{{ $Produto->Cor ?? '—' }}</td>
            <td>{{ $Produto->Genero ?? '—' }}</td>
            <td>{{ $Produto->Valor ?? '—' }}</td>
            <td>{{ $Produto->Marca->Nome ?? '—' }}</td>
            <td>{{ $Produto->Tipo->Nome ?? '—' }}</td>
            <td class="d-flex gap-2">
                <a href="{{ route('Produto.edit', $Produto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                <a href="{{ route('Produto.show', $Produto->id) }}" class="btn btn-sm btn-info">Consultar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
