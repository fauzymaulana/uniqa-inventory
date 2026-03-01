<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Template Import Produk';
    }

    public function array(): array
    {
        // Provide one example row so users understand the format
        return [
            [
                'Produk Contoh',
                'Makanan',
                '15000',
                '100',
                'Deskripsi produk contoh',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_produk',
            'kategori',
            'harga',
            'stok',
            'deskripsi',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header row style
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Example row style (light yellow background)
        $sheet->getStyle('A2:E2')->getFill()->setFillType('solid')
            ->getStartColor()->setRGB('FFFFE0');

        // Add note about the example row
        $sheet->getComment('A2')->getText()->createTextRun('Baris ini adalah contoh, hapus sebelum mengupload.');

        // Add categories note below
        $categories = Category::pluck('name')->join(', ');
        $sheet->setCellValue('A4', 'Catatan:');
        $sheet->setCellValue('A5', '- Kolom nama_produk wajib diisi');
        $sheet->setCellValue('A6', '- SKU, Barcode dan QR Code akan digenerate otomatis oleh sistem');
        $sheet->setCellValue('A7', '- Kolom kategori harus sesuai dengan nama kategori yang tersedia: ' . $categories);
        $sheet->setCellValue('A8', '- Hapus baris contoh (baris ke-2) sebelum mengupload');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->mergeCells('A5:E5');
        $sheet->mergeCells('A6:E6');
        $sheet->mergeCells('A7:E7');
        $sheet->mergeCells('A8:E8');

        return [];
    }
}
