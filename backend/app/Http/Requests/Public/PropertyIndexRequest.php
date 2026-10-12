<?php

namespace App\Http\Requests\Public;

use App\Http\Requests\BaseIndexRequest;

class PropertyIndexRequest extends BaseIndexRequest
{
    protected array $allowedSorts = [
        'created_at', 'id', 'price', 'title', 'sort_order',
    ];

    protected array $allowedFilters = [
        'type' => ['nullable', 'string', 'max:50'],
        'bedrooms' => ['nullable', 'integer', 'min:0'],
        'bathrooms' => ['nullable', 'integer', 'min:0'],
    ];

    protected array $allowedRelations = ['images', 'features'];
}