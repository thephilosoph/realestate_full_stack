<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'array'],
            'question.en' => ['required', 'string'],
            'question.ar' => ['required', 'string'],
            'answer' => ['required', 'array'],
            'answer.en' => ['required', 'string'],
            'answer.ar' => ['required', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}