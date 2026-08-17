<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Simple archive command registered via routes/console.php for environments where App\Console\Commands is not used
Artisan::command('transactions:archive {--years=3} {--chunk=200}', function () {
    $years = (int) $this->option('years');
    $chunk = (int) $this->option('chunk');

    $cutoff = Carbon::today()->subYears($years)->toDateString();
    $this->info("Archiving transactions older than {$cutoff} ({$years} years)");

    $count = Transaction::where('tanggal_pinjam', '<', $cutoff)->count();
    if ($count === 0) {
        $this->info('No transactions to archive.');
        return 0;
    }

    $this->info("Found {$count} transactions to process. Processing in chunks of {$chunk}...");

    Transaction::where('tanggal_pinjam', '<', $cutoff)
        ->orderBy('id')
        ->chunkById($chunk, function ($transactions) {
            DB::beginTransaction();
            try {
                foreach ($transactions as $t) {
                    $row = [
                        'id' => $t->id,
                        'member_id' => $t->member_id ?? null,
                        'book_id' => $t->book_id ?? null,
                        'tanggal_pinjam' => $t->tanggal_pinjam,
                        'tanggal_kembali' => $t->tanggal_kembali,
                        'deadline' => $t->deadline,
                        'status' => $t->status,
                        'denda' => $t->denda ?? 0,
                        'denda_hilang' => $t->denda_hilang ?? 0,
                        'created_at' => $t->created_at,
                        'updated_at' => $t->updated_at,
                        'archived_at' => Carbon::now(),
                    ];

                    DB::table('transactions_archive')->updateOrInsert(['id' => $t->id], $row);

                    DB::table('transactions')->where('id', $t->id)->delete();
                }

                DB::commit();
                $this->info('Chunk processed successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error('Failed processing chunk: ' . $e->getMessage());
                throw $e;
            }
        });

    $this->info('Archiving complete.');
    return 0;
})->describe('Archive old transactions into transactions_archive table (closure command)');

// Backfill command: set tanggal_kembali and denda for transactions that have status=kembali but missing tanggal_kembali or denda
Artisan::command('transactions:backfill-denda', function () {
    $this->info('Backfilling tanggal_kembali and denda for returned transactions...');
    $tarifPerHari = (int) (\App\Models\Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);

    $q = \App\Models\Transaction::where('status', 'kembali')
        ->where(function ($sub) {
            $sub->whereNull('tanggal_kembali')->orWhereNull('denda');
        });

    $count = $q->count();
    $this->info("Found {$count} transactions to backfill");

    $q->chunkById(200, function ($rows) use ($tarifPerHari) {
        foreach ($rows as $t) {
            $returnDate = $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali) : \Carbon\Carbon::parse($t->updated_at ?? $t->created_at);
            try {
                $deadline = \Carbon\Carbon::parse($t->deadline);
                $hariKeterlambatan = $returnDate->gt($deadline) ? $deadline->diffInDays($returnDate) : 0;
                $denda = $hariKeterlambatan * $tarifPerHari;
            } catch (\Exception $e) {
                $denda = 0;
            }

            $t->tanggal_kembali = $t->tanggal_kembali ?? $returnDate->toDateString();
            $t->denda = $t->denda ?? $denda;
            $t->save();
        }
    });

    $this->info('Backfill complete.');
})->describe('Backfill missing tanggal_kembali and denda for returned transactions');
