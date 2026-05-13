<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'collection_id' => ['nullable', 'exists:collections,id'],
            'gender' => ['required', 'in:male,female,unisex'],
            'release_date' => ['nullable', 'date'],

            // Variants (nested array)
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.sku' => ['required', 'string', 'max:255', 'unique:product_variants,sku'],
            'variants.*.color' => ['required', 'string', 'max:255'],
            'variants.*.size' => ['nullable', 'string', 'max:10'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'variants.*.weight' => ['nullable', 'integer', 'min:0'],

            // Tags
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],

            // Images
            'images' => ['nullable', 'array'],
            'images.*.image_url' => ['required', 'string', 'max:2048'],
            'images.*.is_primary' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'variants.required' => 'Produk harus memiliki minimal 1 varian.',
            'variants.*.sku.unique' => 'SKU varian sudah digunakan.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
        ];
    }
}
