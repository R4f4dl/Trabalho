<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\ProdutoController;
use App\Models\Produto;
use Illuminate\Support\Facades\Storage;

// encontra o primeiro produto que possua imagens em array
$produto = Produto::whereNotNull('imagem')->get()->first(function($p){
    return is_array($p->imagem) && count($p->imagem) > 0;
});

if (!$produto) {
    echo "Nenhum produto com imagens encontrado.\n";
    exit(0);
}

echo "Produto selecionado ID: {$produto->id}\n";
$images = is_array($produto->imagem) ? $produto->imagem : [];
foreach ($images as $i => $img) {
    echo "[$i] $img\n";
    // verifica existência no disco se for /storage/
    if (strpos($img, '/storage/') !== false) {
        $rel = ltrim(str_replace('/storage/', '', $img), '/');
        $full = storage_path('app/public/' . $rel);
        echo "    -> disk path: $full ";
        echo file_exists($full) ? "(exists)\n" : "(missing)\n";
    }
}

// cria requisição simulada para remover index 0
$indexToRemove = 0;
$request = Request::create('/produto/' . $produto->id . '/remove-image', 'POST', ['index' => $indexToRemove]);
$request->setMethod('POST');

$controller = new ProdutoController();
try {
    $resp = $controller->removeImage($produto->id, $request);
    echo "\nController respondeu: ";
    if ($resp instanceof \Illuminate\Http\RedirectResponse) {
        echo "Redirect para " . $resp->getTargetUrl() . "\n";
    } else {
        var_export($resp);
        echo "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit(1);
}

// recarrega produto e imprime imagens atualizadas
$produtoFresh = Produto::find($produto->id);
$images2 = is_array($produtoFresh->imagem) ? $produtoFresh->imagem : [];

echo "\nImagens após remoção: (" . count($images2) . ")\n";
foreach ($images2 as $i => $img) {
    echo "[$i] $img\n";
}

echo "\nScript finalizado.\n";
