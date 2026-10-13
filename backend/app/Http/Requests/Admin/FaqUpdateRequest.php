<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['sometimes', 'array'],
            'question.en' => ['required_with:question', 'string'],
            'question.ar' => ['required_with:question', 'string'],
            'answer' => ['sometimes', 'array'],
            'answer.en' => ['required_with:answer', 'string'],
            'answer.ar' => ['required_with:answer', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}