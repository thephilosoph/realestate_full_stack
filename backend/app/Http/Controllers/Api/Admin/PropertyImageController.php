<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyImageResource;
use App\Models\PropertyImage;
use App\Services\ImageService;
use Illuminate\Http\Response;

class PropertyImageController extends Controller
{
    public function __construct(protected ImageService $imageService) {}

    public function destroy(int $id)
    {
        $image = PropertyImage::query()->findOrFail($id);
        $this->imageService->deleteImage($image);

        return response()->json(['message' => 'Image deleted.'], Response::HTTP_OK);
    }

    public function setPrimary(int $id)
    {
        $image = PropertyImage::query()->findOrFail($id);
        $this->imageService->setPrimary($image);

        return new PropertyImageResource($image->fresh());
    }
}