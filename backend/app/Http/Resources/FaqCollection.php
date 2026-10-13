<?php
// app/Http/Resources/FaqCollection.php
namespace App\Http\Resources;

class FaqCollection extends BaseResourceCollection
{
    public $collects = FaqResource::class;
}