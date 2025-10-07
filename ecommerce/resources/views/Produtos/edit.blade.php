@extends('layout')

@section('conteudo')

<h1>Alterar Produto</h1>
<form method="POST" action="{{ route('Produto.update', ['Produto' => $Produto->id]) }}">
    @CSRF
    @METHOD('PUT')
    <a href="{{ route('Produto.index')}}" class="btn btn-success mb-3">Novo Registro</a>


        {{-- Tamanho --}}
        <div class="form-group">
            <label for="Tamanho">Tamanho:</label>
            <select name="Tamanho" id="Tamanho" class="form-control">
                <option value="">--</option>
                <option value="PP" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'PP') ? 'selected' : '' }}>PP</option>
                <option value="P" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'P') ? 'selected' : '' }}>P</option>
                <option value="M" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'M') ? 'selected' : '' }}>M</option>
                <option value="G" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'G') ? 'selected' : '' }}>G</option>
                <option value="GG" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'GG') ? 'selected' : '' }}>GG</option>
                <option value="XG" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'XG') ? 'selected' : '' }}>XG</option>
                <option value="XGG" {{ (old('Tamanho', $Produto->Tamanho ?? '') == 'XGG') ? 'selected' : '' }}>XGG</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Cor">Cor:</label>
            <input value="{{ $Produto->Cor ?? '—' }}" name="Cor" id="Cor" type="text" class="form-control">
        </div>

        {{-- Gênero --}}
        <div class="form-group">
            <label for="Genero">Gênero:</label>
            <select name="Genero" id="Genero" class="form-control">
                <option value="Masculino" {{ (old('Genero', $Produto->Genero ?? '') == 'Masculino') ? 'selected' : '' }}>Masculino</option>
                <option value="Feminino" {{ (old('Genero', $Produto->Genero ?? '') == 'Feminino') ? 'selected' : '' }}>Feminino</option>
                <option value="Unissex" {{ (old('Genero', $Produto->Genero ?? '') == 'Unissex') ? 'selected' : '' }}>Unissex</option>
            </select>
        </div>

        {{-- Marca --}}
        <div class="form-group">
            <label for="id_marca">Marca:</label>
            <select name="id_marca" id="id_marca" class="form-control">
                @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}" 
                        {{ (old('id_marca', $Produto->id_marca ?? '') == $marca->id) ? 'selected' : '' }}>
                        {{ $marca->Nome }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tipo --}}
        <div class="form-group">
            <label for="id_tipo">Tipo:</label>
            <select name="id_tipo" id="id_tipo" class="form-control">
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" 
                        {{ (old('id_tipo', $Produto->id_tipo ?? '') == $tipo->id) ? 'selected' : '' }}>
                        {{ $tipo->Nome }}
                    </option>
                @endforeach
            </select>
        </div> 


        {{-- Upload de imagens (múltiplas) --}}
        <div class="form-group">
            <label for="imagem">Imagens</label>
            <input type="file" name="imagem[]" id="imagem" class="form-control" multiple accept="image/*">
            <small class="form-text text-muted">Máx 5 imagens, 4MB cada (configurado no controller).</small>

            @if(!empty($Produto->imagens))
                <div class="mt-2">
                    <label>Imagens atuais:</label>
                    <div class="d-flex flex-wrap">
                        @foreach($Produto->imagens as $img)
                            <div class="me-2 mb-2">
                                <img src="{{ asset('storage/'.$img) }}" alt="Imagem do Produto" style="width: 100px; height: auto; border:1px solid #ccc; padding:2px;">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
      <button type="submit" class="btn btn-primary">Salvar</button>
</form>


@endsection