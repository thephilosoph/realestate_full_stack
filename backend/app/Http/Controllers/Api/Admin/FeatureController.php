<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureIndexRequest;
use App\Http\Requests\Admin\FeatureStoreRequest;
use App\Http\Requests\Admin\FeatureUpdateRequest;
use App\Http\Resources\FeatureCollection;
use App\Http\Resources\FeatureResource;
use App\Services\FeatureService;
use Illuminate\Http\Response;

class FeatureController extends Controller
{
    public function __construct(protected FeatureService $service) {}

    public function index(FeatureIndexRequest $request)
    {
        $items = $this->service->list($request->toFilters());

        return new FeatureCollection($items);
    }

    public function store(FeatureStoreRequest $request)
    {
        $item = $this->service->create($request->validated());

        return (new FeatureResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $item = $this->service->findOrFail($id);

        return new FeatureResource($item);
    }

    public function update(FeatureUpdateRequest $request, int $id)
    {
        $item = $this->service->update($id, $request->validated());

        return new FeatureResource($item);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Feature deleted.']);
    }
}