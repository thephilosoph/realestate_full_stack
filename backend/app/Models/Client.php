<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Client extends Model
{
    use HasTranslations;

        public array $translatable = [
        'name',
    ];

    protected $fillable = [
        'name', 'logo_path', 'website_url', 'is_published', 'sort_order',
    ];

    protected $appends = ['logo_url'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? Storage::disk('public')->url($this->logo_path)
            : null;
    }
}
