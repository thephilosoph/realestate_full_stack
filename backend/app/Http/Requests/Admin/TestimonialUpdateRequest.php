<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'array'],
            'name.en' => ['required_with:name', 'string', 'max:255'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            'title' => ['sometimes', 'array'],
            'title.en' => ['required_with:title', 'string', 'max:255'],
            'title.ar' => ['required_with:title', 'string', 'max:255'],
            'body' => ['sometimes', 'array'],
            'body.en' => ['required_with:body', 'string'],
            'body.ar' => ['required_with:body', 'string'],
            'location' => ['sometimes', 'nullable', 'array'],
            'location.en' => ['nullable', 'string', 'max:255'],
            'location.ar' => ['nullable', 'string', 'max:255'],
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}