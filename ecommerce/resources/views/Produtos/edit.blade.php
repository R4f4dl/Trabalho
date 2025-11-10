@extends('layout')

@section('conteudo')

<h1>Alterar Produto</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('Produto.update', ['Produto' => $Produto->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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

        <div class="form-group">
            <label for="Valor">Valor (R$):</label>
            <input value="{{ old('Valor', $Produto->Valor ?? '') }}" name="Valor" id="Valor" type="text" class="form-control" placeholder="0.00">
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


        {{-- Upload de imagens (múltiplas) e substituição individual --}}
        <div class="form-group">
            <label for="imagens">Adicionar novas imagens (até completar 3)</label>
            <input type="file" name="imagens[]" id="imagens" class="form-control" multiple accept="image/*">
            <small class="form-text text-muted">Máx 3 imagens no total, 4MB cada. Você também pode substituir imagens existentes abaixo.</small>

            @if(!empty($Produto->imagem) && is_array($Produto->imagem))
                <div class="mt-3">
                    <label>Imagens atuais (clique em "Substituir" para trocar uma imagem específica):</label>
                    <div class="d-flex flex-wrap">
                        @foreach($Produto->imagem as $idx => $img)
                            <div class="me-2 mb-3" style="text-align:center;">
                                <img src="{{ $img }}" alt="Imagem do Produto" style="width: 120px; height: auto; border:1px solid #ccc; padding:4px; display:block; margin-bottom:6px;">
                                <div style="display:flex; gap:6px; justify-content:center;">
                                    <label class="btn btn-sm btn-outline-primary" style="display:inline-block;">
                                        Substituir
                                        <input type="file" name="imagens_replace[{{ $idx }}]" style="display:none;" accept="image/*">
                                    </label>

                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="if(confirm('Remover esta imagem?')) submitRemoveImage({{ $idx }})">Remover</button>
                                </div>
                                <div style="font-size:12px; color:#666; margin-top:4px;">Posição #{{ $idx + 1 }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(!empty($Produto->imagem))
                <div class="mt-2">
                    <img src="{{ $Produto->imagem }}" alt="Imagem do Produto" style="width: 120px; height: auto; border:1px solid #ccc; padding:4px; display:block; margin-bottom:6px;">
                    <label class="btn btn-sm btn-outline-primary">
                        Substituir
                        <input type="file" name="imagens_replace[0]" style="display:none;" accept="image/*">
                    </label>
                </div>
            @endif
        </div>
      <button type="submit" class="btn btn-primary">Salvar</button>
</form>


@endsection

@push('scripts')
<script>
function submitRemoveImage(index) {
    const action = "{{ route('Produto.removeImage', ['Produto' => $Produto->id]) }}";
    const token = "{{ csrf_token() }}";
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = action;
    form.style.display = 'none';

    const inputToken = document.createElement('input');
    inputToken.type = 'hidden';
    inputToken.name = '_token';
    inputToken.value = token;
    form.appendChild(inputToken);

    const inputIndex = document.createElement('input');
    inputIndex.type = 'hidden';
    inputIndex.name = 'index';
    inputIndex.value = index;
    form.appendChild(inputIndex);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush