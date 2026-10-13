<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
        'role',
        'bio',
    ];

    protected $fillable = [
        'name', 'role', 'bio', 'photo_path', 'is_published', 'sort_order',
    ];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        if (str_starts_with($this->photo_path, 'http')) {
            return $this->photo_path;
        }

        return Storage::disk('public')->url($this->photo_path);
    }
    
}
