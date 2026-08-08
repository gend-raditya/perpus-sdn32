<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;

    // Menangkap lemparan parameter filter dari Controller
    public function __construct($startDate, $endDate, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    // Query data sesuai filter tanggal & status
    public function query()
    {
        $query = Transaction::with(['member', 'book'])
            ->whereBetween('tanggal_pinjam', [$this->startDate, $this->endDate]);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->latest();
    }

    // Judul Header Kolom di File Excel
    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NISN',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Denda',
            'Status',
        ];
    }

    // Format Isian Data per Baris
    public function map($transaction): array
    {
        return [
            $transaction->member->nama ?? '-',
            $transaction->member->nisn ?? '-',
            $transaction->book->judul ?? '-',
            $transaction->tanggal_pinjam,
            $transaction->tanggal_kembali ?? '-',
            $transaction->denda ? 'Rp ' . number_format($transaction->denda, 0, ',', '.') : '-',
            ucfirst($transaction->status),
        ];
    }
}
