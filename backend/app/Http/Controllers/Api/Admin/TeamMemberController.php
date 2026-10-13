<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberIndexRequest;
use App\Http\Requests\Admin\TeamMemberStoreRequest;
use App\Http\Requests\Admin\TeamMemberUpdateRequest;
use App\Http\Resources\TeamMemberCollection;
use App\Http\Resources\TeamMemberResource;
use App\Services\TeamMemberService;
use Illuminate\Http\Response;

class TeamMemberController extends Controller
{
    public function __construct(protected TeamMemberService $service) {}

    public function index(TeamMemberIndexRequest $request)
    {
        $items = $this->service->listAdmin($request->toFilters());

        return new TeamMemberCollection($items);
    }

    public function store(TeamMemberStoreRequest $request)
    {
        $item = $this->service->create($request->validated());

        return (new TeamMemberResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $item = $this->service->findOrFail($id);

        return new TeamMemberResource($item);
    }

    public function update(TeamMemberUpdateRequest $request, int $id)
    {
        $item = $this->service->update($id, $request->validated());

        return new TeamMemberResource($item);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Team member deleted.']);
    }
}