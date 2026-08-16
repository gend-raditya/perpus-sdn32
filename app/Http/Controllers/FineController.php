<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index()
    {
        $tarifPerHari = $this->getTarifPerHari();

        $transaksiAktif = Transaction::with(['member', 'book'])
            ->where('status', 'pinjam')
            ->get();

        $totalDendaBelumBayar = 0;

        $dataDenda = $transaksiAktif->map(function ($transaksi) use ($tarifPerHari, &$totalDendaBelumBayar) {
            $tanggalDeadline = Carbon::parse($transaksi->deadline);
            $hariIni = Carbon::today();

            if ($hariIni->gt($tanggalDeadline)) {
                $hariKeterlambatan = $tanggalDeadline->diffInDays($hariIni);
            } else {
                $hariKeterlambatan = 0;
            }

            $jumlahDenda = $hariKeterlambatan * $tarifPerHari;
            $totalDendaBelumBayar += $jumlahDenda;

            $transaksi->hari_telat = $hariKeterlambatan;
            $transaksi->total_denda = $jumlahDenda;

            return $transaksi;
        });

        $jumlahMuridDenda = $dataDenda->filter(function ($item) {
            return $item->total_denda > 0;
        })->unique('member_id')->count();

        return view('fines.index', [
            'dataDenda' => $dataDenda,
            'totalDendaBelumBayar' => $totalDendaBelumBayar,
            'jumlahMuridDenda' => $jumlahMuridDenda,
            'tarifPerHari' => $tarifPerHari,
        ]);
    }

    public function store(Request $request)
    {
        return $this->updateTarif($request);
    }

    public function updateTarif(Request $request)
    {
        $validated = $request->validate([
            'tarif_per_hari' => ['required', 'integer', 'min:0'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'tarif_denda_per_hari'],
            ['value' => (string) $validated['tarif_per_hari']]
        );

        return redirect()->route('fines.index')->with('success', 'Tarif denda berhasil diperbarui.');
    }

    protected function getTarifPerHari(): int
    {
        return (int) (Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
    }

    /**
     * Menangani proses pengembalian buku secara massal (bulk) via checkbox
     */
    public function kembalikanBulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id' // Pastikan ID valid di database
        ]);

        foreach ($request->ids as $id) {
            $transaksi = Transaction::find($id);
            if ($transaksi) {
                $transaksi->status = 'kembali';
                $transaksi->save();
            }
        }

        return redirect()->back()->with('success', 'Buku yang dipilih berhasil dikembalikan.');
    }
}
