<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeHelper
{
    /**
     * Generate QR Code for a product (SVG, no imagick required).
     */
    public static function generateQrCode($product)
    {
        try {
            $payload = $product->barcode ?: $product->sku;

            return 'data:image/svg+xml;base64,' . base64_encode(
                QrCode::size(200)->format('svg')->generate($payload)
            );
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
