<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class MapFacilityResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'store_id',
        'name',
        'type',
        'floor',
        'x',
        'y'
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        //mapからブース詳細が見たい場合は記述する
    ];
}
