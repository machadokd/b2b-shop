<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function findAll(): Collection
    {
        return Customer::with('user')->get();
    }

    public function findById(int $id): ?Model
    {
        return Customer::find($id);
    }

    public function create(array $data): Model
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data): Model
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);

        return $customer;
    }

    public function delete(int $id): bool
    {
        return Customer::destroy($id) > 0;
    }

    public function findWithUser(int $id): ?Customer
    {
        return Customer::with(['user', 'addresses'])->find($id);
    }

    public function findByUserId(int $userId): ?Customer
    {
        return Customer::where('user_id', $userId)->first();
    }
}
