<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($customer?->user_id)],
            'password' => ['nullable', 'string', 'min:8'],
            'company_name' => ['required', 'string', 'max:255'],
            'nif' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }
}
