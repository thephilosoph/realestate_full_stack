<?php
namespace App\Http\Resources;

class FaqResource extends BaseResource
{
    protected function fields($request): array
    {
        return [
            'question' => $this->translated('question'),
            'answer' => $this->translated('answer'),
            'is_published' => $this->is_published,
            'sort_order' => $this->sort_order,
        ];
    }
}