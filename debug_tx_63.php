<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id=63;
$t=App\Models\Transaction::with(['member','book'])->find($id);
if(!$t){ echo "NOT_FOUND\n"; exit; }
echo json_encode($t->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
