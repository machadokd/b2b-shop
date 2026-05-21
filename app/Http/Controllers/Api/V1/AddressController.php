<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAddressRequest;
use App\Http\Requests\Api\V1\UpdateAddressRequest;
use App\Http\Resources\Api\V1\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return AddressResource::collection(Address::with('customer')->paginate(15));
    }

    public function store(StoreAddressRequest $request): AddressResource
    {
        $address = Address::create($request->validated());

        return new AddressResource($address);
    }

    public function show(Address $address): AddressResource
    {
        return new AddressResource($address);
    }

    public function update(UpdateAddressRequest $request, Address $address): AddressResource
    {
        $address->update($request->validated());

        return new AddressResource($address);
    }

    public function destroy(Address $address): JsonResponse
    {
        $address->delete();

        return response()->json(null, 204);
    }
}
