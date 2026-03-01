<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'amount_received' => 'required|numeric|min:0|decimal:0,2',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Daftar produk harus ada',
            'items.min' => 'Minimal harus ada 1 produk',
            'items.*.product_id.required' => 'ID produk harus diisi',
            'items.*.product_id.exists' => 'Produk tidak ditemukan',
            'items.*.quantity.required' => 'Jumlah produk harus diisi',
            'items.*.quantity.min' => 'Jumlah minimal 1',
            'amount_received.required' => 'Jumlah uang yang diterima harus diisi',
            'amount_received.numeric' => 'Jumlah uang harus berupa angka',
        ];
    }
}
