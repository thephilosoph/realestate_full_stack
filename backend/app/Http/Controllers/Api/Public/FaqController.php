<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\FaqIndexRequest;
use App\Http\Resources\FaqCollection;
use App\Services\FaqService;

class FaqController extends Controller
{
    public function __construct(protected FaqService $service) {}

    public function index(FaqIndexRequest $request)
    {
        $items = $this->service->listPublished($request->toFilters());

        return new FaqCollection($items);
    }
}