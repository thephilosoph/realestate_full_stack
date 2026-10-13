<?php
namespace App\Services;

use App\Models\Faq;
use App\Repositories\FaqRepository;
use Illuminate\Database\Eloquent\Builder;

class FaqService extends BaseService
{
    public function __construct(FaqRepository $repository)
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

    public function findPublishedOrFail(int $id): Faq
    {
        return Faq::query()->where('is_published', true)->findOrFail($id);
    }
}