<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laporan Bulanan';
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'Bulan' => $item['Bulan'],
                'Total Transaksi' => $item['Total Transaksi'],
                'Pendapatan (Rp)' => $item['Pendapatan (Rp)'],
                'Pengeluaran (Rp)' => $item['Pengeluaran (Rp)'],
                'Balance (Rp)' => $item['Balance (Rp)'],
                'Rata-rata per Transaksi' => $item['Rata-rata per Transaksi'],
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
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ];

        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Format currency columns with Indonesian number format
        $lastRow = count($this->data) + 1;
        $currencyFormat = '#,##0';
        $sheet->getStyle("C2:F{$lastRow}")->getNumberFormat()->setFormatCode($currencyFormat);

        // Apply border to all data rows
        $sheet->getStyle("A1:F{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

        // Alternating row colors + conditional balance coloring
        foreach ($this->data as $index => $item) {
            $row = $index + 2;
            $balance = $item['Balance (Rp)'];

            // Balance column (E) conditional color
            if ($balance >= 0) {
                $sheet->getStyle("E{$row}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('C6EFCE'); // Light green
                $sheet->getStyle("E{$row}")->getFont()->getColor()->setRGB('276221'); // Dark green
            } else {
                $sheet->getStyle("E{$row}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('FFC7CE'); // Light red
                $sheet->getStyle("E{$row}")->getFont()->getColor()->setRGB('9C0006'); // Dark red
            }

            // Alternating row background (except balance column which has its own color)
            if ($index % 2 === 1) {
                $sheet->getStyle("A{$row}:D{$row}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('F2F2F2');
                $sheet->getStyle("F{$row}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('F2F2F2');
            }

            // Bold font for total row data
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        }

        // Center-align columns B and Total Transaksi
        $sheet->getStyle("B2:B{$lastRow}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal('left');
        $sheet->getStyle("C2:F{$lastRow}")->getAlignment()->setHorizontal('right');

        return [];
    }
}

