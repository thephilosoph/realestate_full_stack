<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'array'],
            'title.en' => ['required_with:title', 'string', 'max:255'],
            'title.ar' => ['required_with:title', 'string', 'max:255'],

            'description' => ['sometimes', 'nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],

            'location' => ['sometimes', 'nullable', 'array'],
            'location.en' => ['nullable', 'string', 'max:255'],
            'location.ar' => ['nullable', 'string', 'max:255'],

            'price' => ['sometimes', 'numeric', 'min:0'],
            'bedrooms' => ['sometimes', 'integer', 'min:0'],
            'bathrooms' => ['sometimes', 'integer', 'min:0'],
            'type' => ['sometimes', 'nullable', 'string', 'max:50'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],

            'feature_ids' => ['sometimes', 'array'],
            'feature_ids.*' => ['integer', Rule::exists('features', 'id')],

            'images' => ['nullable', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}