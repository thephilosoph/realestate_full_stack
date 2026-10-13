<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class FeatureResource extends BaseResource
{
    protected function fields(Request $request): array
    {
        return [
            'name' => $this->translated('name'),
            'sort_order' => $this->when(
                $this->pivot !== null,
                fn () => $this->pivot->sort_order
            ),
        ];
    }

    protected function includeMeta($request): array
    {
        return [
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}