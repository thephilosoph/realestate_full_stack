<?php
namespace App\Repositories;

use App\Models\TeamMember;

class TeamMemberRepository extends BaseRepository
{
    public function __construct(TeamMember $model)
    {
        parent::__construct($model);
        $this->filterable = ['is_published'];
        $this->searchable = ['name', 'role'];
        $this->sortable = ['created_at', 'id', 'sort_order'];
        $this->defaultSort = 'sort_order';
        $this->defaultDirection = 'asc';
    }
}