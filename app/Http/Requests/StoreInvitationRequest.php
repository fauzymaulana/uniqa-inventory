<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'cashier']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invitation_category_id' => 'required|exists:invitation_categories,id',
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'price'                  => 'nullable|numeric|min:0',
            'thumbnail'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_demo'             => 'nullable|max:20480',  // Size check only, file type checked in controller
            'link'                   => 'nullable|url|max:500',
            'is_active'              => 'nullable|in:on,off,0,1,true,false',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Handle checkbox - convert to boolean string
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => false]);
        } else {
            $this->merge(['is_active' => true]);
        }
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'video_demo.mimes'   => 'Video harus dalam format MP4, MPEG, MOV, atau QuickTime.',
            'video_demo.max'     => 'Video terlalu besar. Maksimal 20MB.',
            'video_demo.file'    => 'Video harus berupa file yang valid.',
            'thumbnail.max'      => 'Thumbnail terlalu besar. Maksimal 5MB.',
            'thumbnail.image'    => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes'    => 'Format gambar harus JPEG, PNG, GIF, atau WEBP.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Log::warning('Validation failed', [
            'errors' => $validator->errors()->all(),
            'input_keys' => array_keys($this->all()),
            'has_video' => $this->hasFile('video_demo'),
        ]);

        parent::failedValidation($validator);
    }
}
