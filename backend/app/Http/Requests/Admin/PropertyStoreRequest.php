<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],

            'location' => ['nullable', 'array'],
            'location.en' => ['nullable', 'string', 'max:255'],
            'location.ar' => ['nullable', 'string', 'max:255'],

            'price' => ['required', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],

            'feature_ids' => ['nullable', 'array'],
            'feature_ids.*' => ['integer', Rule::exists('features', 'id')],

            'images' => ['nullable', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}