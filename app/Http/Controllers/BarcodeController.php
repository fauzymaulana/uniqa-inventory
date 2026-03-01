<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    /**
     * Generate and display QR Code for a product.
     */
    public function qrcode(Product $product): Response
    {
        // Create directory if not exists
        $dirPath = storage_path('app/public/qrcodes');
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        // Generate QR code file
        $filePath = $dirPath . '/' . $product->id . '.png';
        
        if (!file_exists($filePath)) {
            try {
                // If a barcode string is defined on the product, generate QR containing
                // only that barcode string so scanners return the expected numeric/text code.
                $payload = $product->barcode ? $product->barcode : json_encode([
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'price' => $product->price,
                ]);

                QrCode::size(300)
                    ->format('png')
                    ->generate($payload, $filePath);
            } catch (\Exception $e) {
                // Fallback - generate on the fly
                $payload = $product->barcode ? $product->barcode : json_encode([
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'price' => $product->price,
                ]);

                $qrCode = QrCode::size(300)
                    ->format('png')
                    ->generate($payload);

                return response($qrCode, 200, [
                    'Content-Type' => 'image/png',
                    'Cache-Control' => 'public, max-age=31536000',
                ]);
            }
        }

        if (file_exists($filePath)) {
            return response(file_get_contents($filePath), 200, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        abort(404, 'QR Code not found');
    }

    /**
     * Generate and display Barcode for a product.
     */
    public function barcode(Product $product): Response
    {
        // Create directory if not exists
        $dirPath = storage_path('app/public/barcodes');
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $filePath = $dirPath . '/' . $product->barcode . '.png';

        // Generate barcode file if not exists
        if (!file_exists($filePath)) {
            try {
                $barcodeGenerator = new BarcodeGeneratorPNG();
                file_put_contents($filePath, $barcodeGenerator->getBarcode($product->barcode, BarcodeGeneratorPNG::TYPE_CODE_128));
            } catch (\Exception $e) {
                return response('Barcode generation failed', 404);
            }
        }

        if (file_exists($filePath)) {
            return response(file_get_contents($filePath), 200, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        abort(404, 'Barcode not found');
    }

    /**
     * Generate single product label for download.
     */
    public function generateLabel(Product $product)
    {
        return view('labels.product-label', compact('product'));
    }

    /**
     * Export multiple product labels.
     */
    public function exportLabels()
    {
        $productIds = request()->input('product_ids', []);

        if (empty($productIds)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 produk');
        }

        $products = Product::whereIn('id', $productIds)
            ->get();

        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        return view('labels.export-labels', compact('products'));
    }
}

