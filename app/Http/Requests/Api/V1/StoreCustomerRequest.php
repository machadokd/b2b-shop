<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:customers,user_id'],
            'company_name' => ['required', 'string', 'max:255'],
            'nif' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
            'is_blocked' => ['boolean'],
        ];
    }
}
