<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\TeamMemberIndexRequest;
use App\Http\Resources\TeamMemberCollection;
use App\Services\TeamMemberService;

class TeamMemberController extends Controller
{
    public function __construct(protected TeamMemberService $service) {}

    public function index(TeamMemberIndexRequest $request)
    {
        $items = $this->service->listPublished($request->toFilters());

        return new TeamMemberCollection($items);
    }
}