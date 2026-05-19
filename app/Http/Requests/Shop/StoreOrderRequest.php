<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (empty(session('cart', []))) {
                    $validator->errors()->add('cart', 'O carrinho está vazio.');
                }

                /** @var \App\Models\User $user */
                $user = $this->user();
                $customerId = $user->customer?->id;

                if ($customerId) {
                    $addressBelongs = \App\Models\Address::where('id', $this->input('address_id'))
                        ->where('customer_id', $customerId)
                        ->exists();

                    if (! $addressBelongs) {
                        $validator->errors()->add('address_id', 'A morada selecionada não é válida.');
                    }
                }
            },
        ];
    }
}
