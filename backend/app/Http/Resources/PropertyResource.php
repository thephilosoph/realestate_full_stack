<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PropertyResource extends BaseResource
{
    protected function fields(Request $request): array
{
    // List shape only for GET collections; create/update/show return the full shape.
    $isList = $request->isMethod('GET') && ! $request->route('id');

    if ($isList) {
        return [
            'title'       => $this->translated('title'),
            'price'       => $this->price,
            'bedrooms'    => $this->bedrooms,
            'bathrooms'   => $this->bathrooms,
            'type'        => $this->type,
            'location'    => $this->translated('location'),
            // short excerpt only:
            'description' => $this->translated('description'), 
            'primary_image' => $this->whenLoaded('images', function () {
                $img = $this->images->firstWhere('is_primary', true)
                    ?? $this->images->first();

                return $img ? [
                    'id'  => $img->id,
                    'url' => $img->url, 
                ] : null;
            }),
        ];
    }

    return [
        'title'       => $this->translated('title'),
        'description' => $this->translated('description'),
        'location'    => $this->translated('location'),
        'price'       => $this->price,
        'bedrooms'    => $this->bedrooms,
        'bathrooms'   => $this->bathrooms,
        'type'        => $this->type,
        'is_published'=> $this->is_published,
        'sort_order'  => $this->sort_order,
    ];
}

protected function includeRelations(Request $request): array
{
    $isDetail = ! $request->isMethod('GET') || (bool) $request->route('id');

    return [
        'images' => $this->when(
            $isDetail && $this->relationLoaded('images'),
            fn () => PropertyImageResource::collection($this->images)
        ),
        'features' => $this->when(
            $isDetail && $this->relationLoaded('features'),
            fn () => FeatureResource::collection($this->features)
        ),
    ];
}
}