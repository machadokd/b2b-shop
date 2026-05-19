<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'nif' => ['sometimes', 'required', 'string', 'max:20'],
            'phone' => ['sometimes', 'required', 'string', 'max:20'],
            'is_blocked' => ['boolean'],
        ];
    }
}
