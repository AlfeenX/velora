<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:products,slug,' . $this->route('product')?->id],
            'description' => ['nullable', 'string'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'collection_id' => ['nullable', 'exists:collections,id'],
            'gender' => ['sometimes', 'in:male,female,unisex'],
            'release_date' => ['nullable', 'date'],

            // Variants (nested array)
            'variants' => ['sometimes', 'array', 'min:1'],
            'variants.*.id' => ['nullable', 'exists:product_variants,id'],
            'variants.*.sku' => ['required_with:variants', 'string', 'max:255'],
            'variants.*.color' => ['required_with:variants', 'string', 'max:255'],
            'variants.*.size' => ['nullable', 'string', 'max:10'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],
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
            'variants.*.sku.required_with' => 'SKU wajib diisi untuk setiap varian.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
        ];
    }
}
