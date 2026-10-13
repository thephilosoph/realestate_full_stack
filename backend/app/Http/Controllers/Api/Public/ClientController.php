<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ClientIndexRequest;
use App\Http\Resources\ClientCollection;
use App\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(protected ClientService $service) {}

    public function index(ClientIndexRequest $request)
    {
        $items = $this->service->listPublished($request->toFilters());

        return new ClientCollection($items);
    }
}