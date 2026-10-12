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

    protected function includeMeta(Request $request): array
    {
        return [];
    }
}