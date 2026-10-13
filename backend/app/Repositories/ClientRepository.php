<?php
namespace App\Repositories;

use App\Models\Client;

class ClientRepository extends BaseRepository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
        $this->filterable = ['is_published'];
        $this->searchable = ['name'];
        $this->sortable = ['created_at', 'id', 'sort_order'];
        $this->defaultSort = 'sort_order';
        $this->defaultDirection = 'asc';
    }
}