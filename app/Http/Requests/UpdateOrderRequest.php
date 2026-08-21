<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'required', 'integer', Rule::exists((new Customer)->getTable(), 'id')],
            'status' => ['sometimes', 'required', Rule::enum(OrderStatus::class)],
        ];
    }
}
