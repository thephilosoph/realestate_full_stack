<?php
namespace App\Http\Requests\Public;

use App\Http\Requests\BaseIndexRequest;

class TeamMemberIndexRequest extends BaseIndexRequest
{
    protected array $allowedSorts = ['sort_order', 'created_at', 'id'];
    protected array $allowedFilters = [];
    protected array $allowedRelations = [];
}