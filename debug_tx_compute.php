<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = $argv[1] ?? 63;
$t = App\Models\Transaction::find($id);
if (!$t) { echo "NOT_FOUND\n"; exit; }
$deadline = \Carbon\Carbon::parse($t->deadline);
$compare = \Carbon\Carbon::parse($t->tanggal_kembali);
var_dump(['deadline'=>$deadline->toDateTimeString(),'compare'=>$compare->toDateTimeString()]);
var_dump('compare_gt_deadline', $compare->gt($deadline));
var_dump('compare->diffInDays(deadline)', $compare->diffInDays($deadline));
var_dump('deadline->diffInDays(compare)', $deadline->diffInDays($compare));
var_dump('diffInDaysFiltered?');
