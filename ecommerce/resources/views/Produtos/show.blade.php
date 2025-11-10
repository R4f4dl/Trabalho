@extends('layout')

@section('conteudo')

<h1>Produtos Cadastrados</h1>

{{-- Formulário de exclusão --}}
<form method="POST" action="{{ route('Produto.destroy', $Produto->id) }}">
    @csrf
    @method('DELETE')

    {{-- Botão para criar novo registro --}}
    <a href="{{ route('Produto.index') }}" class="btn btn-success mb-3">Novo Registro</a>

    <div class="mb-3">
        <label class="form-label">ID:</label>
        <input disabled value="{{ $Produto->id }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Imagem:</label>
        @if(is_array($Produto->imagem) && count($Produto->imagem))
            <div class="d-flex gap-2">
                @foreach($Produto->imagem as $img)
                    <img src="{{ $img }}" style="max-width:120px; max-height:90px; object-fit:cover;" alt="img" />
                @endforeach
            </div>
        @elseif($Produto->imagem)
            <img src="{{ $Produto->imagem }}" style="max-width:120px; max-height:90px; object-fit:cover;" alt="img" />
        @else
            <input disabled value="—" type="text" class="form-control">
        @endif
    </div>

    <div class="mb-3">
        <label class="form-label">Tamanho:</label>
        <input disabled value="{{ $Produto->Tamanho ?? '—' }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Cor:</label>
        <input disabled value="{{ $Produto->Cor ?? '—' }}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Gênero:</label>
        <input disabled value="{{ $Produto->Genero ?? '—' }}" type="text" class="form-control">
    </div>

    {{-- Marca --}}
    <div class="mb-3">
        <label class="form-label">Marca:</label>
        <input disabled value="{{ $Produto->marca->Nome ?? '—' }}" type="text" class="form-control">
    </div>

    {{-- Tipo --}}
    <div class="mb-3">
        <label class="form-label">Tipo:</label>
        <input disabled value="{{ $Produto->tipo->Nome ?? '—' }}" type="text" class="form-control">
    </div>

    {{-- Ações --}}
    <p>Deseja excluir esse registro?</p>
    <button type="submit" class="btn btn-danger">Sim</button>
    <a href="#" class="btn btn-secondary" onClick="history.back()">Não</a>

</form>

@endsection
