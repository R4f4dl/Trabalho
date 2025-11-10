<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Produto;

function printLine($s){ echo $s . "\n"; }

try {
    $produtos = Produto::limit(5)->get();
    printLine("Found produtos: " . $produtos->pluck('id')->join(', '));

    if ($produtos->isEmpty()) {
        printLine('No produtos found.');
        exit(0);
    }

    $p = $produtos->first();
    $id = $p->id;
    printLine("Testing update on produto id={$id}");
    $original = $p->toArray();
    $p->Cor = ($p->Cor === 'TESTE_COR' ? 'TESTE_COR2' : 'TESTE_COR');
    $p->Valor = ($p->Valor ?? 0) + 0.01;
    $p->save();
    $fresh = Produto::find($id);
    printLine("Update saved. Cor now: {$fresh->Cor}, Valor: {$fresh->Valor}");

    // Try to delete a product likely to have estoque references (id 6 from logs)
    $tryId = 6;
    $prodTry = Produto::find($tryId);
    if ($prodTry) {
        printLine("Attempting delete of produto id={$tryId}");
        try {
            $prodTry->delete();
            printLine("Delete succeeded for id={$tryId}");
        } catch (Exception $e) {
            printLine("Delete failed for id={$tryId}: " . get_class($e) . ' - ' . $e->getMessage());
        }
    } else {
        printLine("Produto id={$tryId} not found; skipping delete test.");
    }

    // Restore original first product to avoid side effects
    $p2 = Produto::find($id);
    if ($p2) {
        $p2->fill($original);
        $p2->save();
        printLine("Restored original produto id={$id}");
    }

} catch (Exception $e) {
    echo "Exception: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

printLine('Done test.');
