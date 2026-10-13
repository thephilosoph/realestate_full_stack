<?php
namespace App\Repositories;

use App\Models\Faq;

class FaqRepository extends BaseRepository
{
    public function __construct(Faq $model)
    {
        parent::__construct($model);
        $this->filterable = ['is_published'];
        $this->searchable = ['question', 'answer'];
        $this->sortable = ['created_at', 'id', 'sort_order'];
        $this->defaultSort = 'sort_order';
        $this->defaultDirection = 'asc';
    }
}