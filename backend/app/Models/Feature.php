<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
    ];
    
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'feature_property')
                    ->withPivot('sort_order');
    }
}
