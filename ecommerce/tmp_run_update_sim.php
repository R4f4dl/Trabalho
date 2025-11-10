<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\ProdutoController;
use App\Models\Produto;
use App\Models\Marca;
use App\Models\Tipo;

$id = 9;
$produto = Produto::find($id);
if (!$produto) {
    echo "Produto $id not found\n";
    exit(1);
}

// pick first marca and tipo
$marca = Marca::first();
$tipo = Tipo::first();
if (!$marca || !$tipo) {
    echo "Marca or Tipo missing in DB\n";
    exit(1);
}

$data = [
    'Tamanho' => $produto->Tamanho ?? 'M',
    'Cor' => 'SIMULADO_COR',
    'Genero' => $produto->Genero ?? 'Unissex',
    'Valor' => $produto->Valor ? $produto->Valor + 1 : 10.00,
    'id_marca' => $marca->id,
    'id_tipo' => $tipo->id,
];

$request = Request::create('/produto/' . $id, 'POST', $data);
$request->setMethod('PUT');

$controller = new ProdutoController();
try {
    $response = $controller->update($request, $id);
    echo "Controller update returned: ";
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "Redirect to " . $response->getTargetUrl() . "\n";
    } else {
        var_export($response);
        echo "\n";
    }
    $fresh = Produto::find($id);
    echo "After update, Cor= " . ($fresh->Cor ?? 'NULL') . ", Valor= " . ($fresh->Valor ?? 'NULL') . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
