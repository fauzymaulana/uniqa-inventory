<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->products as $product) {
            $data[] = [
                'SKU' => $product->sku,
                'Nama Produk' => $product->name,
                'Kategori' => $product->category->name ?? 'N/A',
                'Harga Satuan' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'Stok' => $product->stock,
                'Nilai Stok' => 'Rp ' . number_format($product->stock * $product->price, 0, ',', '.'),
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama Produk',
            'Kategori',
            'Harga Satuan',
            'Jumlah Stok',
            'Nilai Stok',
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
