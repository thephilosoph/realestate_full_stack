<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\TestimonialIndexRequest;
use App\Http\Resources\TestimonialCollection;
use App\Services\TestimonialService;

class TestimonialController extends Controller
{
    public function __construct(protected TestimonialService $service) {}

    public function index(TestimonialIndexRequest $request)
    {
        $items = $this->service->listPublished($request->toFilters());

        return new TestimonialCollection($items);
    }
}