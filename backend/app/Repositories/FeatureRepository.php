<?php
namespace App\Repositories;

use App\Models\Feature;

class FeatureRepository extends BaseRepository
{
    public function __construct(Feature $model)
    {
        parent::__construct($model);
        $this->filterable = [];
        $this->searchable = ['name'];
        $this->sortable = ['created_at', 'id'];
        $this->defaultSort = 'id';
        $this->defaultDirection = 'asc';
    }
}