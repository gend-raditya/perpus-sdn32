<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tarif = (int) (\App\Models\Setting::where('key','tarif_denda_per_hari')->value('value') ?? 1000);
$rows = App\Models\Transaction::select('id','denda','denda_hilang','status','deadline','tanggal_kembali')->get();
$out=[];
foreach($rows as $r){
    $d=(int) $r->denda; $h=(int) $r->denda_hilang;
    if ($d % max(1,$tarif) !== 0) {
        $out[] = ['id'=>$r->id,'denda'=>$r->denda,'denda_hilang'=>$r->denda_hilang,'status'=>$r->status,'deadline'=>$r->deadline,'tanggal_kembali'=>$r->tanggal_kembali];
    }
}

echo json_encode(['tarif'=>$tarif,'mismatches'=>$out], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
