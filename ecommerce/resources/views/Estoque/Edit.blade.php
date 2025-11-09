@extends('layout')

@section('conteudo')

  <h2>Editar Estoque</h2>
  <form method="post" action="{{ route('Estoque.update', $estoque->id) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
          <label for="id_produtos" class="form-label">Produto:</label>
          <select name="id_produtos" id="id_produtos" class="form-control" required>
              @foreach($produtos as $produto)
                  <option value="{{ $produto->id }}" {{ $produto->id == $estoque->id_produtos ? 'selected' : '' }}>
                      {{ $produto->marca->Nome ?? 'Sem Marca' }} - 
                      {{ $produto->tipo->Nome ?? 'Sem Tipo' }} - 
                      {{ $produto->Cor }} - {{ $produto->Tamanho }}
                  </option>
              @endforeach
          </select>
      </div>

      <div class="mb-3">
          <label for="PrecoCompra" class="form-label">Preço de Compra:</label>
          <input type="number" id="PrecoCompra" name="PrecoCompra" class="form-control" value="{{ $estoque->PrecoCompra }}" required>
      </div>

      <div class="mb-3">
          <label for="PrecoVenda" class="form-label">Preço de Venda Sugerido:</label>
          <input type="number" id="PrecoVenda" name="PrecoVenda" class="form-control" value="{{ $estoque->PrecoVenda }}" required>
      </div>

      <div class="mb-3">
          <label for="Quantidade" class="form-label">Quantidade:</label>
          <input type="number" id="Quantidade" name="Quantidade" class="form-control" value="{{ $estoque->Quantidade }}" required>
      </div>

      <button type="submit" class="btn btn-primary">Atualizar</button>
  </form>

@endsection
