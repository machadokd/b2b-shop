<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAddressRequest;
use App\Http\Requests\Api\V1\UpdateAddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressController extends Controller
{
    public function index(Customer $customer): AnonymousResourceCollection
    {
        return AddressResource::collection($customer->addresses()->paginate(15));
    }

    public function store(StoreAddressRequest $request, Customer $customer): AddressResource
    {
        $address = $customer->addresses()->create($request->validated());

        return new AddressResource($address);
    }

    public function show(Customer $customer, Address $address): AddressResource
    {
        abort_unless($address->customer_id === $customer->id, 404);

        return new AddressResource($address);
    }

    public function update(UpdateAddressRequest $request, Customer $customer, Address $address): AddressResource
    {
        abort_unless($address->customer_id === $customer->id, 404);

        $address->update($request->validated());

        return new AddressResource($address);
    }

    public function destroy(Customer $customer, Address $address): JsonResponse
    {
        abort_unless($address->customer_id === $customer->id, 404);

        $address->delete();

        return response()->json(null, 204);
    }
}
