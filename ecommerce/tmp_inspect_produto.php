<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// list first 10 products with id and imagem to inspect what's stored
$prods = App\Models\Produto::query()->limit(10)->get(['id', 'Tamanho','Cor','Valor','imagem']);
echo json_encode($prods->map(function($p){
	return [
		'id' => $p->id ?? null,
		'Tamanho' => $p->Tamanho ?? null,
		'Cor' => $p->Cor ?? null,
		'Valor' => $p->Valor ?? null,
		'imagem' => $p->imagem ?? null,
	];
})->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

echo "\n\n-- DB column info for Produtos.imagem --\n";
try {
	$col = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM `Produtos` LIKE 'imagem'");
	echo json_encode($col, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Exception $e) {
	echo "Error querying columns: " . $e->getMessage();
}
