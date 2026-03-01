<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|decimal:0,2',
            'stock' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
            'qr_code' => 'nullable|string|unique:products,qr_code',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi',
            'sku.required' => 'SKU produk harus diisi',
            'sku.unique' => 'SKU produk sudah terdaftar',
            'price.required' => 'Harga produk harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'stock.required' => 'Stok produk harus diisi',
            'stock.integer' => 'Stok harus berupa angka bulat',
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak ditemukan',
        ];
    }
}
