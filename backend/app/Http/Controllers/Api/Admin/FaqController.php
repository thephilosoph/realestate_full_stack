<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqIndexRequest;
use App\Http\Requests\Admin\FaqStoreRequest;
use App\Http\Requests\Admin\FaqUpdateRequest;
use App\Http\Resources\FaqCollection;
use App\Http\Resources\FaqResource;
use App\Services\FaqService;
use Illuminate\Http\Response;

class FaqController extends Controller
{
    public function __construct(protected FaqService $service) {}

    public function index(FaqIndexRequest $request)
    {
        $items = $this->service->listAdmin($request->toFilters());

        return new FaqCollection($items);
    }

    public function store(FaqStoreRequest $request)
    {
        $item = $this->service->create($request->validated());

        return (new FaqResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $item = $this->service->findOrFail($id);

        return new FaqResource($item);
    }

    public function update(FaqUpdateRequest $request, int $id)
    {
        $item = $this->service->update($id, $request->validated());

        return new FaqResource($item);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'FAQ deleted.']);
    }
}