<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientIndexRequest;
use App\Http\Requests\Admin\ClientStoreRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    public function __construct(protected ClientService $service) {}

    public function index(ClientIndexRequest $request)
    {
        $items = $this->service->listAdmin($request->toFilters());

        return new ClientCollection($items);
    }

    public function store(ClientStoreRequest $request)
    {
        $item = $this->service->create($request->validated());

        return (new ClientResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id)
    {
        $item = $this->service->findOrFail($id);

        return new ClientResource($item);
    }

    public function update(ClientUpdateRequest $request, int $id)
    {
        $item = $this->service->update($id, $request->validated());

        return new ClientResource($item);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Client deleted.']);
    }
}