<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\PropertyIndexRequest;
use App\Http\Resources\PropertyCollection;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;

class PropertyController extends Controller
{
    public function __construct(protected PropertyService $service) {}

public function index(PropertyIndexRequest $request)
{
    $filters = $request->toFilters();

    if (empty($filters['relations'])) {
        $filters['relations'] = [
            'images' => fn ($q) => $q
                ->where('is_primary', true)
                ->orWhere(function ($q) {
                })
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->limit(1),
        ];
    }

    $filters['relations'] = [
        'images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
    ];

    $items = $this->service->listPublished($filters);

    return new PropertyCollection($items);
}

    public function show(int $id)
    {
        $property = $this->service->findPublishedOrFail($id);

        return new PropertyResource($property);
    }
}