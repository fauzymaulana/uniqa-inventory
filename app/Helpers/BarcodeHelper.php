<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeHelper
{
    /**
     * Generate QR Code for a product.
     */
    public static function generateQrCode($product)
    {
        try {
            $qrCodePath = storage_path("app/public/qrcodes/{$product->id}.png");
            
            if (!file_exists(dirname($qrCodePath))) {
                mkdir(dirname($qrCodePath), 0755, true);
            }

            // Prefer storing the product's barcode string in the QR code when available.
            $payload = $product->barcode ? $product->barcode : json_encode([
                'product_id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
            ]);

            QrCode::size(200)
                ->format('png')
                ->generate($payload, $qrCodePath);

            return asset("storage/qrcodes/{$product->id}.png");
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate Barcode for a product.
     */
    public static function generateBarcode($barcode)
    {
        try {
            $barcodePath = storage_path("app/public/barcodes/{$barcode}.png");
            
            if (!file_exists(dirname($barcodePath))) {
                mkdir(dirname($barcodePath), 0755, true);
            }

            \Picqer\Barcode\BarcodeGenerator::png($barcode, $barcodePath);

            return asset("storage/barcodes/{$barcode}.png");
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse QR code data.
     */
    public static function parseQrCode($data)
    {
        try {
            return json_decode($data, true);
        } catch (\Exception $e) {
            return null;
        }
    }
}
