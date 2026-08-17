<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$out = [];
$out['tarif'] = \App\Models\Setting::where('key','tarif_denda_per_hari')->first();
$out['tarif_hilang'] = \App\Models\Setting::where('key','tarif_denda_buku_hilang')->first();

echo json_encode($out, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
