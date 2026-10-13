<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'client_request_id' => 'required|uuid', 'customer_name' => 'required|string|max:120', 'phone' => 'nullable|string|max:30',
            'delivery_type' => 'required|in:recojo,delivery', 'address' => 'nullable|required_if:delivery_type,delivery|string|max:250',
            'items' => 'required|array|min:1|max:50', 'items.*.product_id' => 'required|integer|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:100'];
    }
}
