<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
        'title',
        'body',
        'location',
    ];
    protected $fillable = [
        'name', 'title', 'body', 'location',
        'avatar_path', 'rating', 'is_published', 'sort_order',
    ];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path
            ? Storage::disk('public')->url($this->avatar_path)
            : null;
    }
}
