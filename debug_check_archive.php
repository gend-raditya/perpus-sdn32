<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = $argv[1] ?? null;
if (!$id) {
    echo "USAGE: php debug_check_archive.php <id>\n";
    exit(1);
}

try {
    $row = \Illuminate\Support\Facades\DB::table('transactions_archive')->where('id', $id)->first();
    if (!$row) {
        echo "ARCHIVE_NOT_FOUND\n";
    } else {
        echo json_encode($row, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        echo "\n";
    }

    $t = App\Models\Transaction::find($id);
    if (!$t) {
        echo "LIVE_NOT_FOUND\n";
    } else {
        echo json_encode($t->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        echo "\n";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
