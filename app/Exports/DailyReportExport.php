<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $transactions;
    protected $startDate;
    protected $endDate;

    public function __construct($transactions, $startDate, $endDate)
    {
        $this->transactions = $transactions;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->transactions as $transaction) {
            $data[] = [
                'ID' => $transaction->id,
                'Tanggal' => $transaction->created_at->format('d-m-Y H:i'),
                'Kasir' => $transaction->user->name ?? 'N/A',
                'Jumlah Item' => $transaction->details->sum('quantity'),
                'Total Harga' => 'Rp ' . number_format($transaction->total_price, 0, ',', '.'),
                'Status' => ucfirst($transaction->status),
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal & Waktu',
            'Nama Kasir',
            'Jumlah Item',
            'Total Harga',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
