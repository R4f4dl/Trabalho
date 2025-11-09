@extends('layout')

@section('conteudo')

  <h2>Detalhes do Estoque</h2>

  <ul class="list-group">
      <li class="list-group-item">
          <strong>Produto:</strong> 
          {{ $estoque->produto->marca->Nome ?? 'Sem Marca' }} - 
          {{ $estoque->produto->tipo->Nome ?? 'Sem Tipo' }} - 
          {{ $estoque->produto->Cor }} - 
          {{ $estoque->produto->Tamanho }}
      </li>
      <li class="list-group-item"><strong>Preço de Compra:</strong> R$ {{ number_format($estoque->PrecoCompra, 2, ',', '.') }}</li>
      <li class="list-group-item"><strong>Preço de Venda:</strong> R$ {{ number_format($estoque->PrecoVenda, 2, ',', '.') }}</li>
      <li class="list-group-item"><strong>Quantidade:</strong> {{ $estoque->Quantidade }}</li>
  </ul>

  <a href="{{ route('Estoque.index') }}" class="btn btn-secondary mt-3">Voltar</a>

@endsection
