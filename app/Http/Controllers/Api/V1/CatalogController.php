<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCatalogRequest;
use App\Http\Requests\Api\V1\UpdateCatalogRequest;
use App\Http\Resources\Api\V1\CatalogResource;
use App\Models\Catalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CatalogController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CatalogResource::collection(Catalog::paginate(15));
    }

    public function store(StoreCatalogRequest $request): CatalogResource
    {
        $catalog = Catalog::create($request->validated());

        return new CatalogResource($catalog);
    }

    public function show(Catalog $catalog): CatalogResource
    {
        return new CatalogResource($catalog);
    }

    public function update(UpdateCatalogRequest $request, Catalog $catalog): CatalogResource
    {
        $catalog->update($request->validated());

        return new CatalogResource($catalog);
    }

    public function destroy(Catalog $catalog): JsonResponse
    {
        $catalog->delete();

        return response()->json(null, 204);
    }
}
