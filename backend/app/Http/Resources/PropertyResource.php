<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PropertyResource extends BaseResource
{
    protected function fields(Request $request): array
    {
        return [
            'title' => $this->translated('title'),
            'description' => $this->translated('description'),
            'location' => $this->translated('location'),
            'price' => $this->price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'type' => $this->type,
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
            'primary_image' => $this->when(
                $this->relationLoaded('images'),
                function () {
                    $primary = $this->images->firstWhere('is_primary', true)
                        ?? $this->images->first();

                    return $primary ? [
                        'id' => $primary->id,
                        'url' => $primary->url,
                    ] : null;
                }
            ),
        ];
    }

    protected function includeRelations(Request $request): array
    {
        return [
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
            'features' => FeatureResource::collection($this->whenLoaded('features')),
        ];
    }
}