<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = $argv[1] ?? null;
if (!$id) {
    echo "USAGE: php debug_transaction.php <id>\n";
    exit(1);
}

try {
    $t = App\Models\Transaction::with(['member','book'])->find($id);
    if (!$t) {
        echo "NOT_FOUND\n";
        exit(0);
    }
    echo json_encode(['transaction' => $t->toArray()], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
