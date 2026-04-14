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
     * Uses SVG format (no imagick required).
     */
    public function qrcode(Product $product): Response
    {
        $payload = $product->barcode ?: $product->sku;

        try {
            $qrCode = QrCode::size(300)
                ->format('svg')
                ->generate($payload);

            return response($qrCode, 200, [
                'Content-Type'  => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        } catch (\Exception $e) {
            abort(500, 'QR Code generation failed: ' . $e->getMessage());
        }
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

