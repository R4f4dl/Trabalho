<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = 9;
$produto = App\Models\Produto::find($id);
if (!$produto) {
    echo "Produto $id not found\n";
    exit(1);
}

$produto->imagem = [
    '/storage/produtos/test1.jpg',
    '/storage/produtos/test2.jpg'
];
$produto->save();

echo json_encode($produto->fresh()->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
