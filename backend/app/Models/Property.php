<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Property extends Model
{
    use HasTranslations;

    public array $translatable = [
        'title',
        'description',
        'location',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'location',
        'bedrooms',
        'bathrooms',
        'type',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_published' => 'boolean',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class)
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }


}
