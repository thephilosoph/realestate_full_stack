<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function storeForProperty(Property $property, array $files, bool $setFirstAsPrimary = true): void
    {
        $startOrder = (int) $property->images()->max('sort_order');
        $hasPrimary = $property->images()->where('is_primary', true)->exists();

        foreach (array_values($files) as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('properties/'.$property->id, 'public');

            $isPrimary = false;
            if ($setFirstAsPrimary && ! $hasPrimary && $index === 0) {
                $isPrimary = true;
                $hasPrimary = true;
            }

            $property->images()->create([
                'path' => $path,
                'is_primary' => $isPrimary,
                'sort_order' => $startOrder + $index + 1,
            ]);
        }
    }

    public function deleteImage(PropertyImage $image): void
    {
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $propertyId = $image->property_id;
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $next = PropertyImage::query()
                ->where('property_id', $propertyId)
                ->orderBy('sort_order')
                ->first();

            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }
    }

    public function setPrimary(PropertyImage $image): void
    {
        PropertyImage::query()
            ->where('property_id', $image->property_id)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);
    }
}