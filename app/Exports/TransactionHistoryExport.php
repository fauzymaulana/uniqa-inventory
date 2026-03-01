<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionHistoryExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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
        return 'Riwayat Transaksi';
    }

    public function collection()
    {
        $no = 1;
        return $this->transactions->map(function ($transaction) use (&$no) {
            $items = $transaction->details->map(function ($detail) {
                return ($detail->product->name ?? 'N/A') . ' (x' . $detail->quantity . ')';
            })->join(', ');

            $paymentMethod = $transaction->payment_method === 'transfer' ? 'Transfer' : 'Cash';

            return [
                'No' => $no++,
                'No. Transaksi' => $transaction->transaction_number,
                'Tanggal' => $transaction->created_at->format('d-m-Y H:i'),
                'Kasir' => $transaction->user->name ?? 'N/A',
                'Item' => $items,
                'Jumlah Item' => $transaction->details->sum('quantity'),
                'Total Harga' => $transaction->total_price,
                'Uang Diterima' => $transaction->amount_received,
                'Kembalian' => $transaction->change,
                'Metode Pembayaran' => $paymentMethod,
                'Status' => ucfirst($transaction->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Transaksi',
            'Tanggal',
            'Kasir',
            'Item',
            'Jumlah Item',
            'Total Harga (Rp)',
            'Uang Diterima (Rp)',
            'Kembalian (Rp)',
            'Metode Pembayaran',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->transactions->count() + 1;

        // Header style
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0070C0']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Currency format
        $sheet->getStyle("G2:I{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("G2:I{$lastRow}")->getAlignment()->setHorizontal('right');

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

        // Title row
        $sheet->insertNewRowBefore(1, 2);
        $sheet->setCellValue('A1', 'Riwayat Transaksi');
        $sheet->setCellValue('A2', 'Periode: ' . $this->startDate->format('d/m/Y') . ' - ' . $this->endDate->format('d/m/Y'));
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

        return [];
    }
}
