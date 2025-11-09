@extends('layout')

@section('conteudo')

  <h2>Nova Entrada de Estoque</h2>
  <form method="post" action="/Estoque">
      @csrf

      <div class="mb-3">
          <label for="id_produtos" class="form-label">Produto:</label>
          <select name="id_produtos" id="id_produtos" class="form-control" required>
              @foreach($produtos as $produto)
                  <option value="{{ $produto->id }}">
                      {{ $produto->marca->Nome ?? 'Sem Marca' }} - 
                      {{ $produto->tipo->Nome ?? 'Sem Tipo' }} - 
                      {{ $produto->Cor }} - {{ $produto->Tamanho }}
                  </option>
              @endforeach
          </select>
      </div>

      <div class="mb-3">
          <label for="PrecoCompra" class="form-label">Preço de Compra:</label>
          <input type="number" id="PrecoCompra" name="PrecoCompra" class="form-control" required>
      </div>

      <div class="mb-3">
          <label for="PrecoVenda" class="form-label">Preço de Venda Sugerido:</label>
          <input type="number" id="PrecoVenda" name="PrecoVenda" class="form-control" required>
      </div>

      <div class="mb-3">
          <label for="Quantidade" class="form-label">Quantidade:</label>
          <input type="number" id="Quantidade" name="Quantidade" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Salvar</button>
  </form>

@endsection
