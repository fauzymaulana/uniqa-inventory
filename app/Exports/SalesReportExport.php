<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function collection()
    {
        $data = [];

        foreach ($this->transactions as $transaction) {
            $paymentMethod = $transaction->payment_method === 'transfer' ? 'Transfer' : 'Cash';

            if ($transaction->details->isEmpty()) {
                $data[] = [
                    'No. Transaksi' => $transaction->transaction_number,
                    'Tanggal' => $transaction->created_at->format('d-m-Y H:i'),
                    'Kasir' => $transaction->user->name ?? 'N/A',
                    'Metode Pembayaran' => $paymentMethod,
                    'SKU Produk' => '-',
                    'Nama Produk' => '-',
                    'Kategori' => '-',
                    'Qty' => 0,
                    'Harga Satuan (Rp)' => 0,
                    'Subtotal (Rp)' => 0,
                    'Total Transaksi (Rp)' => $transaction->total_price,
                ];
            } else {
                foreach ($transaction->details as $detail) {
                    $data[] = [
                        'No. Transaksi' => $transaction->transaction_number,
                        'Tanggal' => $transaction->created_at->format('d-m-Y H:i'),
                        'Kasir' => $transaction->user->name ?? 'N/A',
                        'Metode Pembayaran' => $paymentMethod,
                        'SKU Produk' => $detail->product?->sku ?? '-',
                        'Nama Produk' => $detail->product?->name ?? '-',
                        'Kategori' => $detail->product?->category?->name ?? '-',
                        'Qty' => $detail->quantity,
                        'Harga Satuan (Rp)' => $detail->price,
                        'Subtotal (Rp)' => $detail->price * $detail->quantity,
                        'Total Transaksi (Rp)' => $transaction->total_price,
                    ];
                }
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal & Waktu',
            'Nama Kasir',
            'Metode Pembayaran',
            'SKU Produk',
            'Nama Produk',
            'Kategori',
            'Qty',
            'Harga Satuan (Rp)',
            'Subtotal (Rp)',
            'Total Transaksi (Rp)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Currency format for numeric columns
        $sheet->getStyle("I2:K{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("I2:K{$lastRow}")->getAlignment()->setHorizontal('right');

        // Borders
        $sheet->getStyle("A1:K{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

        // Alternating rows
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 === 0) {
                $sheet->getStyle("A{$i}:K{$i}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }

        // Title rows
        $sheet->insertNewRowBefore(1, 2);
        $sheet->setCellValue('A1', 'Laporan Penjualan Detail');
        $sheet->setCellValue('A2', 'Periode: ' . $this->startDate->format('d/m/Y') . ' - ' . $this->endDate->format('d/m/Y'));
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

        return [];
    }
}
