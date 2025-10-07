@extends('layout')

@section('conteudo')
<div class="container">
    <h1>Criar Produto</h1>

    {{-- mostra erros gerais --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('Produto.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tamanho --}}
        <div class="form-group">
            <label for="Tamanho">Tamanho</label>
            <select name="Tamanho" id="Tamanho" class="form-control">
                <option value="">--</option>
                <option value="PP" {{ old('Tamanho')=='P' ? 'selected':'' }}>PP</option>
                <option value="P" {{ old('Tamanho')=='P' ? 'selected':'' }}>P</option>
                <option value="M" {{ old('Tamanho')=='M' ? 'selected':'' }}>M</option>
                <option value="G" {{ old('Tamanho')=='G' ? 'selected':'' }}>G</option>
                <option value="GG" {{ old('Tamanho')=='GG' ? 'selected':'' }}>GG</option>
                <option value="XG" {{ old('Tamanho')=='G' ? 'selected':'' }}>XG</option>
                <option value="XGG" {{ old('Tamanho')=='GG' ? 'selected':'' }}>XGG</option>
            </select>
        </div>

        {{-- Cor --}}
        <div class="form-group">
            <label for="Cor">Cor</label>
            <input type="text" name="Cor" id="Cor" class="form-control" value="{{ old('Cor') }}">
        </div>

        {{-- Gênero --}}
        <div class="form-group">
            <label for="Genero">Gênero</label>
            <select name="Genero" id="Genero" class="form-control">
                <option value="">--</option>
                <option value="Masculino" {{ old('Genero')=='M' ? 'selected':'' }}>Masculino</option>
                <option value="Feminino" {{ old('Genero')=='F' ? 'selected':'' }}>Feminino</option>
                <option value="Unissex" {{ old('Genero')=='U' ? 'selected':'' }}>Unissex</option>
            </select>
        </div>

        {{-- Select de Marca --}}
        <label for="id_marca">Marca</label>
        <select name="id_marca" id="id_marca" class="form-control">
            <option value="">Selecione</option>
            @foreach($marcas as $marca)
                <option value="{{ $marca->id }}">{{ $marca->Nome }}</option>
            @endforeach
        </select>

        {{-- Select de Tipo --}}
        <label for="id_tipo">Tipo</label>
        <select name="id_tipo" id="id_tipo" class="form-control">
            <option value="">Selecione</option>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}">{{ $tipo->Nome }}</option>
            @endforeach
        </select>

        {{-- Upload de imagens (múltiplas) --}}
        <div class="form-group">
            <label for="imagem">Imagens (pode selecionar várias)</label>
            <input type="file" name="imagem" id="imagem" class="form-control" multiple accept="image/*">
            <small class="form-text text-muted">Máx 5 imagens, 4MB cada (configurado no controller).</small>
        </div>

        {{-- Se quiser, campos alt para cada imagem (opcional): você teria que enviar via JS ou inputs adicionais --}}
        {{-- Para simplicidade, omiti inputs extras; controller permite alt se enviados como imagens_alt[] --}}

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection