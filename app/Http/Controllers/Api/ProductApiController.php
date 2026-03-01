<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    /**
     * Get all products with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);

        $products = Product::with('category')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }

    /**
     * Get single product details.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category', 'stockAdjustments');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'description' => $product->description,
                'price' => (float) $product->price,
                'stock' => $product->stock,
                'barcode' => $product->barcode,
                'qr_code' => $product->qr_code,
                'category' => [
                    'id' => $product->category->id ?? null,
                    'name' => $product->category->name ?? null,
                ],
                'last_adjustments' => $product->stockAdjustments->take(5)->map(function ($adjustment) {
                    return [
                        'id' => $adjustment->id,
                        'type' => $adjustment->type,
                        'quantity' => $adjustment->adjustment_value,
                        'reason' => $adjustment->reason,
                        'created_at' => $adjustment->created_at,
                    ];
                }),
            ],
        ]);
    }

    /**
     * DataTables server-side processing for admin products table.
     */
    public function datatables(Request $request): JsonResponse
    {
        try {
            $columns = ['id', 'name', 'sku', 'category', 'price', 'stock', 'barcode'];

            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 10));
            $search = $request->input('search.value');

            $query = Product::with('category');

            $recordsTotal = Product::count();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                      ->orWhere('sku', 'ilike', "%{$search}%")
                      ->orWhere('barcode', 'ilike', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            // Ordering
            if ($request->has('order')) {
                $order = $request->get('order')[0];
                $colIndex = intval($order['column']);
                $dir = $order['dir'] === 'asc' ? 'asc' : 'desc';
                $colName = $columns[$colIndex] ?? 'name';
                if ($colName === 'category') {
                    $query->join('categories', 'products.category_id', '=', 'categories.id')
                        ->orderBy('categories.name', $dir)
                        ->select('products.*');
                } else {
                    $query->orderBy($colName, $dir);
                }
            }

            $data = $query->skip($start)->take($length)->get();

            $result = $data->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'category' => $product->category->name ?? '-',
                    'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'stock' => $product->stock,
                    'barcode' => $product->barcode,
                    'actions' => view('products.partials.actions', compact('product'))->render(),
                ];
            });

            return response()->json([
                'draw' => intval($request->get('draw', 0)),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            logger()->error('DataTables products.datatables error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
