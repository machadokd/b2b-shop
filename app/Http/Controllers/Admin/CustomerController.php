<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::with('user')->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $user->customer()->create([
            'company_name' => $data['company_name'],
            'nif' => $data['nif'],
            'phone' => $data['phone'],
            'is_blocked' => false,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Cliente criado com sucesso.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['user', 'addresses', 'orders']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $customer->load('user');

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();

        $customer->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $customer->user->update(['password' => Hash::make($data['password'])]);
        }

        $customer->update([
            'company_name' => $data['company_name'],
            'nif' => $data['nif'],
            'phone' => $data['phone'],
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->user->delete();
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Cliente eliminado com sucesso.');
    }

    public function toggleBlocked(Customer $customer): RedirectResponse
    {
        $customer->update(['is_blocked' => ! $customer->is_blocked]);

        return back()->with('success', 'Estado do cliente atualizado.');
    }
}
