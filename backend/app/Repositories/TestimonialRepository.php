<?php
namespace App\Repositories;

use App\Models\Testimonial;

class TestimonialRepository extends BaseRepository
{
    public function __construct(Testimonial $model)
    {
        parent::__construct($model);
        $this->filterable = ['is_published'];
        $this->searchable = ['name', 'title', 'body'];
        $this->sortable = ['created_at', 'id', 'sort_order', 'rating'];
        $this->defaultSort = 'sort_order';
        $this->defaultDirection = 'asc';
    }
}