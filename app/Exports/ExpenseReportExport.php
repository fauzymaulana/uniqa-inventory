<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $expenses;
    protected $startDate;
    protected $endDate;

    public function __construct($expenses, $startDate, $endDate)
    {
        $this->expenses = $expenses;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Laporan Pengeluaran';
    }

    public function collection()
    {
        $no = 1;
        return $this->expenses->map(function ($expense) use (&$no) {
            $typeLabel = match ($expense->type) {
                'operasional' => 'Operasional',
                'asset' => 'Asset',
                'stok_barang' => 'Stok Barang',
                default => $expense->type,
            };

            $statusLabel = match ($expense->status) {
                'selesai' => 'Selesai',
                'belum_tuntas' => 'Belum Tuntas',
                default => $expense->status,
            };

            return [
                'No' => $no++,
                'Tanggal' => $expense->created_at->format('d-m-Y H:i'),
                'Kegiatan' => $expense->activity,
                'Tipe' => $typeLabel,
                'Kategori' => $expense->category ? $expense->category->name : '-',
                'Deskripsi' => $expense->description ?? '-',
                'Biaya' => $expense->amount,
                'Status' => $statusLabel,
                'Dibuat Oleh' => $expense->user->name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kegiatan',
            'Tipe',
            'Kategori',
            'Deskripsi',
            'Biaya (Rp)',
            'Status',
            'Dibuat Oleh',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->expenses->count() + 1;

        // Header style
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Currency format for Biaya column (G)
        $sheet->getStyle("G2:G{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("G2:G{$lastRow}")->getAlignment()->setHorizontal('right');

        // Borders
        $sheet->getStyle("A1:I{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

        // Alternating rows
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 === 0) {
                $sheet->getStyle("A{$i}:I{$i}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        // Title row before data
        $sheet->insertNewRowBefore(1, 2);
        $sheet->setCellValue('A1', 'Laporan Pengeluaran');
        $sheet->setCellValue('A2', 'Periode: ' . $this->startDate->format('d/m/Y') . ' - ' . $this->endDate->format('d/m/Y'));
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

        return [];
    }
}
