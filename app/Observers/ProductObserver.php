<?php

namespace App\Observers;

use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->generateBarcodeAndQr($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->generateBarcodeAndQr($product);
    }

    /**
     * Generate barcode and QR code for product.
     */
    private function generateBarcodeAndQr(Product $product): void
    {
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
                } catch (\Exception $e) {
                    // Log error but don't fail
                    \Log::warning('Barcode generation failed for product ' . $product->id);
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
            } catch (\Exception $e) {
                // Log error but don't fail
                \Log::warning('QR code generation failed for product ' . $product->id);
            }
        }
    }
}
