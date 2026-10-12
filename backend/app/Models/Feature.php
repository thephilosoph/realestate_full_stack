<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
    ];
    
    protected $fillable = ['name'];

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)
            ->withPivot('sort_order');
    }
}
