<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
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
            'courier' => ['sometimes', 'string', 'max:255'],
            'tracking_number' => ['sometimes', 'string', 'max:255'],
            'shipped_at' => ['sometimes', 'date'],
            'delivered_at' => ['sometimes', 'date', 'after_or_equal:shipped_at'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'delivered_at.after_or_equal' => 'Tanggal diterima harus setelah tanggal pengiriman.',
        ];
    }
}
