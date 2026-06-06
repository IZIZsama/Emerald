<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapFacilities extends Model
{
    protected $table = 'map_facilities';

    protected $fillable = [
        'store_id',
        'name',
        'type',
        'floor',
        'x',
        'y',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'floor' => 'integer',
        'x' => 'integer',
        'y' => 'integer',
    ];
}
