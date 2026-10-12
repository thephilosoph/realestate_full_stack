<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return array_merge(
            ['id' => $this->id],
            $this->fields($request),
            $this->includeRelations($request),
            ['meta' => $this->includeMeta($request)]
        );
    }


    abstract protected function fields(Request $request): array;

    protected function includeRelations(Request $request): array
    {
        return [];
    }

    protected function includeMeta(Request $request): array
    {
        return [
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    protected function translated(string $field): array
    {
        if (method_exists($this->resource, 'getTranslations')) {
            return $this->resource->getTranslations($field);
        }

        $value = $this->{$field};

        return is_array($value) ? $value : ['en' => $value, 'ar' => $value];
    }
}