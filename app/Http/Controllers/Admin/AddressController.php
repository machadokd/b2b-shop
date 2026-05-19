<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAddressRequest;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function create(Customer $customer): View
    {
        return view('admin.addresses.create', compact('customer'));
    }

    public function store(StoreAddressRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('is_default')) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create([
            'recipient_name' => $data['recipient_name'],
            'address_line' => $data['address_line'],
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'country' => $data['country'],
            'nif' => $data['nif'] ?? null,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Morada adicionada com sucesso.');
    }

    public function edit(Customer $customer, Address $address): View
    {
        return view('admin.addresses.edit', compact('customer', 'address'));
    }

    public function update(StoreAddressRequest $request, Customer $customer, Address $address): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('is_default')) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $address->update([
            'recipient_name' => $data['recipient_name'],
            'address_line' => $data['address_line'],
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'country' => $data['country'],
            'nif' => $data['nif'] ?? null,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Morada atualizada com sucesso.');
    }

    public function destroy(Customer $customer, Address $address): RedirectResponse
    {
        $address->delete();

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Morada eliminada com sucesso.');
    }
}
