<?php
namespace App\Http\Resources;

class TestimonialResource extends BaseResource
{
    protected function fields($request): array
    {
        return [
            'name' => $this->translated('name'),
            'title' => $this->translated('title'),
            'body' => $this->translated('body'),
            'location' => $this->translated('location'),
            'rating' => $this->rating,
            'avatar_url' => $this->avatar_url,
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
        ];
    }
}