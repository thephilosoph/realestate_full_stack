<?php 

namespace App\Repositories;

use App\Models\Property;

class PropertyRepository extends BaseRepository
{
    public function __construct(Property $model)
    {
        parent::__construct($model);

        $this->filterable = ['is_published', 'type', 'bedrooms', 'bathrooms'];
        $this->searchable = ['title', 'description', 'location'];
        $this->sortable = ['created_at', 'id', 'price', 'title', 'sort_order'];
        $this->defaultSort = 'sort_order';
        $this->defaultDirection = 'asc';
    }
}