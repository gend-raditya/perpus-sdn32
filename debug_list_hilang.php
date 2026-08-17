<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $books = App\Models\Book::where('status','hilang')->get();
    $out = [];
    foreach ($books as $b) {
        $trx = App\Models\Transaction::where('book_id',$b->id)->latest('id')->first();
        $out[] = [
            'book' => $b->toArray(),
            'last_transaction' => $trx ? $trx->toArray() : null,
        ];
    }
    echo json_encode($out, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
