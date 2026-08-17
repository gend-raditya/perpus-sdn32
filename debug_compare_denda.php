<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tarif = (int)(\App\Models\Setting::where('key','tarif_denda_per_hari')->value('value') ?? 1000);
$rows = App\Models\Transaction::with('book')->whereNotNull('tanggal_kembali')->get();
$out=[];
foreach($rows as $r){
    try{
        $deadline = \Carbon\Carbon::parse($r->deadline);
        $compare = \Carbon\Carbon::parse($r->tanggal_kembali);
        $days = $compare->gt($deadline) ? $compare->diffInDays($deadline) : 0;
    } catch (Exception $e){ $days = null; }
    $expected = ($days===null)?null:($days * $tarif);
    $out[] = ['id'=>$r->id,'status'=>$r->status,'deadline'=>$r->deadline,'tanggal_kembali'=>$r->tanggal_kembali,'days'=>$days,'expected'=>$expected,'stored'=>$r->denda];
}

echo json_encode(['tarif'=>$tarif,'rows'=>$out], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
