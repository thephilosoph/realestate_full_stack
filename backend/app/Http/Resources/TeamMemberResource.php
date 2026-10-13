<?php
namespace App\Http\Resources;

class TeamMemberResource extends BaseResource
{
    protected function fields($request): array
    {
        return [
            'name' => $this->translated('name'),
            'role' => $this->translated('role'),
            'bio' => $this->translated('bio'),
            'photo_url' => $this->photo_url,
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
        ];
    }
}