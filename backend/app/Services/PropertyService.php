<?php

namespace App\Services;

use App\Models\Property;
use App\Repositories\PropertyRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PropertyService extends BaseService
{
    public function __construct(
        PropertyRepository $repository,
        protected ImageService $imageService
    ) {
        $this->repository = $repository;
    }

    public function listPublished(array $filters = [])
    {
        return $this->list(
            $filters,
            scope: fn (Builder $q) => $q->where('is_published', true)
        );
    }

    public function listAdmin(array $filters = [])
    {
        return $this->list($filters);
    }

    public function findPublishedOrFail(int $id): Property
    {
        return Property::query()
            ->where('is_published', true)
            ->with(['images', 'features'])
            ->findOrFail($id);
    }

    public function create(array $data): Property
    {
        return DB::transaction(function () use ($data) {
            $featureIds = $data['feature_ids'] ?? [];
            $images = $data['images'] ?? [];
            unset($data['feature_ids'], $data['images']);

            if (! isset($data['user_id']) && auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            $property = $this->repository->create($data);

            if ($featureIds !== []) {
                $sync = [];
                foreach (array_values($featureIds) as $i => $featureId) {
                    $sync[$featureId] = ['sort_order' => $i];
                }
                $property->features()->sync($sync);
            }

            if ($images !== []) {
                $this->imageService->storeForProperty($property, $images);
            }

            return $property->load(['images', 'features']);
        });
    }

    public function update(int $id, array $data): Property
    {
        return DB::transaction(function () use ($id, $data) {
            $featureIds = array_key_exists('feature_ids', $data)
                ? $data['feature_ids']
                : null;
            $images = $data['images'] ?? [];
            unset($data['feature_ids'], $data['images']);

            $property = $this->repository->update($id, $data);

            if (is_array($featureIds)) {
                $sync = [];
                foreach (array_values($featureIds) as $i => $featureId) {
                    $sync[$featureId] = ['sort_order' => $i];
                }
                $property->features()->sync($sync);
            }

            if ($images !== []) {
                $this->imageService->storeForProperty($property, $images, setFirstAsPrimary: false);
            }

            return $property->load(['images', 'features']);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $property = $this->repository->findOrFail($id);

            foreach ($property->images as $image) {
                $this->imageService->deleteImage($image);
            }

            $property->features()->detach();

            return $this->repository->delete($id);
        });
    }
}