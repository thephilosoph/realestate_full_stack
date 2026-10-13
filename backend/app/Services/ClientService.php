<?php
namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientService extends BaseService
{
    public function __construct(ClientRepository $repository)
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
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                $data['logo_path'] = $data['logo']->store('clients', 'public');
            }
            unset($data['logo']);

            return $this->repository->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            /** @var Client $item */
            $item = $this->repository->findOrFail($id);

            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                if ($item->logo_path) {
                    Storage::disk('public')->delete($item->logo_path);
                }
                $data['logo_path'] = $data['logo']->store('clients', 'public');
            }
            unset($data['logo']);

            return $this->repository->update($id, $data);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            /** @var Client $item */
            $item = $this->repository->findOrFail($id);
            if ($item->logo_path) {
                Storage::disk('public')->delete($item->logo_path);
            }
            return $this->repository->delete($id);
        });
    }
}