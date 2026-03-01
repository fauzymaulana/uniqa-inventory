<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TopProductsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        $data = [];
        $rank = 1;

        foreach ($this->products as $product) {
            $data[] = [
                'Peringkat' => $rank++,
                'SKU' => $product['sku'],
                'Nama Produk' => $product['name'],
                'Harga Satuan' => 'Rp ' . number_format($product['price'], 0, ',', '.'),
                'Jumlah Terjual' => $product['quantity'],
                'Total Penjualan' => 'Rp ' . number_format($product['revenue'], 0, ',', '.'),
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Peringkat',
            'SKU',
            'Nama Produk',
            'Harga Satuan',
            'Jumlah Terjual',
            'Total Penjualan',
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
