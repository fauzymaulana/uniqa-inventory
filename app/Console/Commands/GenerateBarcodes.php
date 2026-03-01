<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;

class GenerateBarcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barcode:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate barcodes and QR codes for all products';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $products = Product::all();
        
        $this->info('Generating barcodes and QR codes for ' . $products->count() . ' products...');

        foreach ($products as $product) {
            // Generate Barcode
            if ($product->barcode) {
                $barcodeDir = storage_path('app/public/barcodes');
                if (!file_exists($barcodeDir)) {
                    mkdir($barcodeDir, 0755, true);
                }

                $barcodePath = $barcodeDir . '/' . $product->barcode . '.png';

                if (!file_exists($barcodePath)) {
                    try {
                        $barcodeGenerator = new BarcodeGeneratorPNG();
                        file_put_contents($barcodePath, $barcodeGenerator->getBarcode($product->barcode, BarcodeGeneratorPNG::TYPE_CODE_128));
                        $this->line("✓ Barcode generated for: {$product->name} ({$product->barcode})");
                    } catch (\Exception $e) {
                        $this->error("✗ Failed to generate barcode for: {$product->name}");
                    }
                }
            }

            // Generate QR Code
            $qrDir = storage_path('app/public/qrcodes');
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0755, true);
            }

            $qrPath = $qrDir . '/' . $product->id . '.png';

            if (!file_exists($qrPath)) {
                try {
                    QrCode::size(300)
                        ->format('png')
                        ->generate(json_encode([
                            'product_id' => $product->id,
                            'sku' => $product->sku,
                            'name' => $product->name,
                            'price' => $product->price,
                        ]), $qrPath);
                    $this->line("✓ QR Code generated for: {$product->name}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to generate QR code for: {$product->name}");
                }
            }
        }

        $this->info('✓ Barcode and QR code generation completed!');
        return 0;
    }
}
