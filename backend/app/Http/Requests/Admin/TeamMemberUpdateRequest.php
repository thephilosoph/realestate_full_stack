<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamMemberUpdateRequest extends FormRequest
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
            'role' => ['sometimes', 'array'],
            'role.en' => ['required_with:role', 'string', 'max:255'],
            'role.ar' => ['required_with:role', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'array'],
            'bio.en' => ['nullable', 'string'],
            'bio.ar' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}