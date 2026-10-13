<?php
namespace App\Http\Requests\Public;

use App\Http\Requests\BaseIndexRequest;

class TestimonialIndexRequest extends BaseIndexRequest
{
    protected array $allowedSorts = ['sort_order', 'created_at', 'id', 'rating'];
    protected array $allowedFilters = [];
    protected array $allowedRelations = [];
}