<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function printResult($title, $rows) {
    echo "\n--- $title ---\n";
    foreach ($rows as $row) {
        if (is_array($row) || is_object($row)) {
            echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo $row . "\n";
        }
    }
}

try {
    $prodCols = DB::select("SHOW COLUMNS FROM `produtos`");
    printResult('produtos columns', $prodCols);

    $estoqueCols = DB::select("SHOW COLUMNS FROM `estoque`");
    printResult('estoque columns', $estoqueCols);

    $prodCreate = DB::select("SHOW CREATE TABLE `produtos`");
    printResult('produtos create', $prodCreate);

    $estoqueCreate = DB::select("SHOW CREATE TABLE `estoque`");
    printResult('estoque create', $estoqueCreate);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";
