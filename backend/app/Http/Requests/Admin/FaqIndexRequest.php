<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseIndexRequest;

class FaqIndexRequest extends BaseIndexRequest
{
    protected array $allowedSorts = ['created_at', 'id', 'sort_order'];
    protected array $allowedFilters = [
        'is_published' => ['nullable', 'boolean'],
    ];
    protected array $allowedRelations = [];
}