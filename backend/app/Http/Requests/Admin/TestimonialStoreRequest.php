<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['required', 'string', 'max:255'],
            'body' => ['required', 'array'],
            'body.en' => ['required', 'string'],
            'body.ar' => ['required', 'string'],
            'location' => ['nullable', 'array'],
            'location.en' => ['nullable', 'string', 'max:255'],
            'location.ar' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}