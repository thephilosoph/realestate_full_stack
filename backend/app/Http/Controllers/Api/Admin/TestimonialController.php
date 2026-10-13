<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialIndexRequest;
use App\Http\Requests\Admin\TestimonialStoreRequest;
use App\Http\Requests\Admin\TestimonialUpdateRequest;
use App\Http\Resources\TestimonialCollection;
use App\Http\Resources\TestimonialResource;
use App\Services\TestimonialService;
use Illuminate\Http\Response;

class TestimonialController extends Controller
{
    public function __construct(protected TestimonialService $service) {}

    public function index(TestimonialIndexRequest $request)
    {
        $items = $this->service->listAdmin($request->toFilters());

        return new TestimonialCollection($items);
    }

    public function store(TestimonialStoreRequest $request)
    {
        $item = $this->service->create($request->validated());

        return (new TestimonialResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $item = $this->service->findOrFail($id);

        return new TestimonialResource($item);
    }

    public function update(TestimonialUpdateRequest $request, int $id)
    {
        $item = $this->service->update($id, $request->validated());

        return new TestimonialResource($item);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Testimonial deleted.']);
    }
}