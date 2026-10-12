<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseIndexRequest extends FormRequest
{
    protected array $allowedSorts = ['created_at', 'id'];

    protected array $allowedFilters = [];

    protected array $allowedRelations = [];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'search' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', Rule::in($this->allowedSorts)],
            'direction' => ['nullable', 'in:asc,desc'],
            'sort_locale' => ['nullable', 'in:en,ar'],
            'perPage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'relations' => ['nullable', 'array'],
            'relations.*' => ['string', Rule::in($this->allowedRelations)],
        ];

        foreach ($this->allowedFilters as $filter => $config) {
            $rules[$filter] = $config;
        }

        return $rules;
    }

    public function toFilters(): array
    {
        $data = $this->validated();

        $filterKeys = array_keys($this->allowedFilters);
        $filters = [];

        foreach ($filterKeys as $key) {
            if (array_key_exists($key, $data) && $data[$key] !== null && $data[$key] !== '') {
                $filters[$key] = $data[$key];
            }
        }

        return [
            'search' => $data['search'] ?? null,
            'sort' => $data['sort'] ?? null,
            'direction' => $data['direction'] ?? 'desc',
            'sort_locale' => $data['sort_locale'] ?? 'en',
            'perPage' => isset($data['perPage']) ? (int) $data['perPage'] : 15,
            'relations' => $data['relations'] ?? [],
            'filters' => $filters,
        ];
    }
}