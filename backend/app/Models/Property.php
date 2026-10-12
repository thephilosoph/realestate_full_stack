<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Property extends Model
{
    use HasTranslations;

    public array $translatable = [
        'title',
        'description',
        'location',
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_property')
                    ->withPivot('sort_order')
                    ->orderBy('pivot_sort_order');
    }

    protected $casts = [
        'is_published' => 'boolean',
        'price' => 'decimal:2',
    ];
}
