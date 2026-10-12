<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropertyIndexRequest;
use App\Http\Requests\Admin\PropertyStoreRequest;
use App\Http\Requests\Admin\PropertyUpdateRequest;
use App\Http\Resources\PropertyCollection;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;
use Illuminate\Http\Response;

class PropertyController extends Controller
{
    public function __construct(protected PropertyService $service) {}

    public function index(PropertyIndexRequest $request)
    {
        $filters = $request->toFilters();

        if (empty($filters['relations'])) {
            $filters['relations'] = ['images', 'features'];
        }

        $items = $this->service->listAdmin($filters);

        return new PropertyCollection($items);
    }

    public function store(PropertyStoreRequest $request)
    {
        $property = $this->service->create($request->validated());

        return (new PropertyResource($property))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $property = $this->service->findOrFail($id);
        $property->load(['images', 'features']);

        return new PropertyResource($property);
    }

    public function update(PropertyUpdateRequest $request, int $id)
    {
        $property = $this->service->update($id, $request->validated());

        return new PropertyResource($property);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Property deleted.'], Response::HTTP_OK);
    }
}