<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date as PhpSpreadsheetDate;
use App\Models\Setting;
use Carbon\Carbon;

class ReportsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithEvents
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
            'Denda (Rp)',
            'Status',
        ];
    }

    // Format Isian Data per Baris
    public function map($transaction): array
    {
        // Prepare dates as Excel serials (so column formatting works)
        $pinjam = null;
        $kembali = null;
        try {
            if (!empty($transaction->tanggal_pinjam)) {
                $pinjam = PhpSpreadsheetDate::PHPToExcel(\Carbon\Carbon::parse($transaction->tanggal_pinjam)->toDateTime());
            }
            if (!empty($transaction->tanggal_kembali)) {
                $kembali = PhpSpreadsheetDate::PHPToExcel(\Carbon\Carbon::parse($transaction->tanggal_kembali)->toDateTime());
            }
        } catch (\Exception $e) {
            $pinjam = $transaction->tanggal_pinjam;
            $kembali = $transaction->tanggal_kembali ?? null;
        }

        // Hitung denda sama dengan logika di halaman Denda/Reports
        try {
            // If transaction is marked lost, prefer the stored denda_hilang (do not include late fee)
            if (strtolower(trim((string)$transaction->status)) === 'hilang') {
                if (isset($transaction->denda_hilang) && $transaction->denda_hilang !== null) {
                    $denda = (int) $transaction->denda_hilang;
                } elseif (isset($transaction->denda) && $transaction->denda !== null) {
                    // fallback: if only denda stored, but status is hilang, prefer denda_hilang removed — use denda_hilang if available, else fall back to denda
                    $denda = (int) $transaction->denda;
                } else {
                    $denda = 0;
                }
            } else {
                // Prefer stored denda if exists (persisted at time of return); otherwise compute on the fly
                if (isset($transaction->denda) && $transaction->denda !== null) {
                    $denda = (int) $transaction->denda;
                } else {
                    $tarifPerHari = (int) (Setting::where('key', 'tarif_denda_per_hari')->value('value') ?? 1000);
                    $deadline = $transaction->deadline ? Carbon::parse($transaction->deadline) : null;
                    $compareDate = $transaction->tanggal_kembali ? Carbon::parse($transaction->tanggal_kembali) : Carbon::today();
                    $hariKeterlambatan = 0;
                    if ($deadline && $compareDate->gt($deadline)) {
                        $hariKeterlambatan = $deadline->diffInDays($compareDate);
                    }
                    $denda = $hariKeterlambatan * $tarifPerHari;
                }
            }
        } catch (\Exception $e) {
            $denda = 0;
        }

        return [
            // Prefer nama_lengkap, fallback ke nama
            $transaction->member->nama_lengkap ?? $transaction->member->nama ?? '-',
            $transaction->member->nisn ?? '-',
            $transaction->book->judul ?? '-',
            $pinjam,
            $kembali,
            $denda,
            ucfirst($transaction->status),
        ];
    }

    // Column formats: dates and numeric currency
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => '#,##0', // number with thousand separator
        ];
    }

    // Styling and post-processing via AfterSheet event
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header style: bold, background, center
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '000000'], 'size' => 12],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
                ]);

                // Freeze header row
                $sheet->freezePane('A2');

                // Auto-filter
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->setAutoFilter('A1:' . $highestColumn . $highestRow);

                // Borders for the table range
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'DDDDDD']
                        ]
                    ]
                ]);

                // Alignment per column
                $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Set a slightly larger row height for header
                $sheet->getRowDimension(1)->setRowHeight(24);
            }
        ];
    }
}
