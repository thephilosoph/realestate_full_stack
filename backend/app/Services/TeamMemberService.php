<?php
namespace App\Services;

use App\Models\TeamMember;
use App\Repositories\TeamMemberRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamMemberService extends BaseService
{
    public function __construct(TeamMemberRepository $repository)
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
            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                $data['photo_path'] = $data['photo']->store('team', 'public');
            }
            unset($data['photo']);

            return $this->repository->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            /** @var TeamMember $item */
            $item = $this->repository->findOrFail($id);

            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                if ($item->photo_path) {
                    Storage::disk('public')->delete($item->photo_path);
                }
                $data['photo_path'] = $data['photo']->store('team', 'public');
            }
            unset($data['photo']);

            return $this->repository->update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            /** @var TeamMember $item */
            $item = $this->repository->findOrFail($id);
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }
            return $this->repository->delete($id);
        });
    }
}