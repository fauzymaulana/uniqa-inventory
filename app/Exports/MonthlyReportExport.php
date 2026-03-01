<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonthlyReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'Bulan' => $item['Bulan'],
                'Total Transaksi' => $item['Total Transaksi'],
                'Pendapatan (Rp)' => 'Rp ' . number_format($item['Pendapatan (Rp)'], 0, ',', '.'),
                'Pengeluaran (Rp)' => 'Rp ' . number_format($item['Pengeluaran (Rp)'], 0, ',', '.'),
                'Balance (Rp)' => 'Rp ' . number_format($item['Balance (Rp)'], 0, ',', '.'),
                'Rata-rata per Transaksi' => 'Rp ' . number_format($item['Rata-rata per Transaksi'], 0, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Bulan',
            'Total Transaksi',
            'Pendapatan (Rp)',
            'Pengeluaran (Rp)',
            'Balance (Rp)',
            'Rata-rata per Transaksi',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ];

        // Balance column styling (highlight positive with green, negative with red)
        $styles = [
            1 => $headerStyle,
        ];

        // Apply conditional formatting for balance column
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        return $styles;
    }
}
