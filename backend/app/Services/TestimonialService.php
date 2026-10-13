<?php
namespace App\Services;

use App\Models\Testimonial;
use App\Repositories\TestimonialRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestimonialService extends BaseService
{
    public function __construct(TestimonialRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listPublished(array $filters = [])
    {
        return $this->list($filters, fn (Builder $q) => $q->where('is_published', true));
    }

    public function listAdmin(array $filters = [])
    {
        return $this->list($filters);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $data['avatar_path'] = $data['avatar']->store('testimonials', 'public');
            }
            unset($data['avatar']);

            return $this->repository->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            /** @var Testimonial $item */
            $item = $this->repository->findOrFail($id);

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                if ($item->avatar_path) {
                    Storage::disk('public')->delete($item->avatar_path);
                }
                $data['avatar_path'] = $data['avatar']->store('testimonials', 'public');
            }
            unset($data['avatar']);

            return $this->repository->update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            /** @var Testimonial $item */
            $item = $this->repository->findOrFail($id);
            if ($item->avatar_path) {
                Storage::disk('public')->delete($item->avatar_path);
            }
            return $this->repository->delete($id);
        });
    }
}