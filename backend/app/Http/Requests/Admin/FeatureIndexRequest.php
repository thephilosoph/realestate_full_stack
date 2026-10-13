<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseIndexRequest;

class FeatureIndexRequest extends BaseIndexRequest
{
    protected array $allowedSorts = ['created_at', 'id'];
    protected array $allowedFilters = [];
    protected array $allowedRelations = [];
}