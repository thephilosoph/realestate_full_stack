<?php
namespace App\Http\Resources;

class ClientResource extends BaseResource
{
    protected function fields($request): array
    {
        return [
            'name' => $this->translated('name'),
            'logo_url' => $this->logo_url,
            'website_url' => $this->website_url,
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
        ];
    }
}