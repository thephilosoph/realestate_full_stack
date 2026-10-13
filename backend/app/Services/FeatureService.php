<?php
namespace App\Services;

use App\Repositories\FeatureRepository;

class FeatureService extends BaseService
{
    public function __construct(FeatureRepository $repository)
    {
        $this->repository = $repository;
    }
}