<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    protected array $filterable = [];


    protected array $searchable = [];

    protected array $translatable = [];

    protected array $sortable = ['created_at', 'id'];

    protected string $defaultSort = 'created_at';

    protected string $defaultDirection = 'desc';

    public function __construct(Model $model)
    {
        $this->model = $model;

        if (property_exists($model, 'translatable') && is_array($model->translatable)) {
            $this->translatable = $model->translatable;
        }
    }

    public function findById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    public function get(
        array $filters = [],
        array $relations = [],
        ?int $perPage = null,
        ?callable $scope = null
    ): Collection|LengthAwarePaginator {
        $query = $this->model->newQuery()->with($relations);

        if ($scope) {
            $scope($query);
        }

        $this->applyFilters($query, $filters);
        $this->applySearch($query, $filters);
        $this->applySorting($query, $filters);

        return $perPage
            ? $query->paginate($perPage)
            : $query->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function make(array $data): Model
    {
        return $this->model->make($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);

        return $record->refresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->findOrFail($id)->delete();
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        $applied = $filters['filters'] ?? [];

        if (! is_array($applied) || $applied === []) {
            return;
        }

        foreach ($applied as $filter => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (! in_array($filter, $this->filterable, true)) {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($filter, $value);
            } else {
                $query->where($filter, $value);
            }
        }
    }

    protected function applySorting(Builder $query, array $filters): void
    {
        $sort = $filters['sort'] ?? $this->defaultSort;
        $direction = strtolower($filters['direction'] ?? $this->defaultDirection);

        if (! in_array($sort, $this->sortable, true)) {
            $sort = $this->defaultSort;
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = $this->defaultDirection;
        }

        if (in_array($sort, $this->translatable, true)) {
            $locale = $filters['sort_locale'] ?? 'en';
            $query->orderBy("{$sort}->{$locale}", $direction);

            return;
        }

        $query->orderBy($sort, $direction);
    }

    protected function applySearch(Builder $query, array $filters): void
    {
        $term = $filters['search'] ?? null;

        if ($term === null || $term === '' || $this->searchable === []) {
            return;
        }

        $term = '%'.$term.'%';

        $query->where(function (Builder $q) use ($term) {
            foreach ($this->searchable as $column) {
                if (in_array($column, $this->translatable, true)) {
                    // Spatie stores JSON: {"en":"...","ar":"..."}
                    $q->orWhere("{$column}->en", 'LIKE', $term)
                      ->orWhere("{$column}->ar", 'LIKE', $term);
                } else {
                    $q->orWhere($column, 'LIKE', $term);
                }
            }
        });
    }
}