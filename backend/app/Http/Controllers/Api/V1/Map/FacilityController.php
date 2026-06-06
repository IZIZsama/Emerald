<?php

namespace App\Http\Controllers\Api\V1\Map;

use App\Http\Controllers\Controller;
use App\Http\Resources\MapFacilityResource;
use App\Models\MapFacilities;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = MapFacilities::query()
            //mapからブース詳細が見たい場合は追記する
            ->select(['id', 'store_id', 'name', 'type', 'floor', 'x', 'y'])
            ->get();

        return MapFacilityResource::collection($facilities);
    }
}
