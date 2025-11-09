@extends('layout')

@section('conteudo')

    <h2>Controle de Estoque</h2>

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

    <a href="{{ route('Estoque.create') }}" class="btn btn-success mb-3">Nova Entrada</a>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Preço Compra</th>
                <th>Preço Venda Sugerido</th>
                <th>Quantidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estoques as $estoque)
                <tr>
                    <td>{{ $estoque->id }}</td>
                    <td>
                        {{ $estoque->produto->marca->Nome ?? 'Sem Marca' }} - 
                        {{ $estoque->produto->tipo->Nome ?? 'Sem Tipo' }} - 
                        {{ $estoque->produto->Cor }} - 
                        {{ $estoque->produto->Tamanho }}
                    </td>
                    <td>R$ {{ number_format($estoque->PrecoCompra, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($estoque->PrecoVenda, 2, ',', '.') }}</td>
                    <td>{{ $estoque->Quantidade }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('Estoque.edit', $estoque->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('Estoque.destroy', $estoque->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
