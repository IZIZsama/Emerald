<?php

namespace App\Http\Controllers\Api\V1\Map;

use App\Http\Controllers\Controller;
use App\Http\Resources\MapFacilityResource;
use App\Models\MapFacilities;
use Illuminate\Http\JsonResponse;

class FacilityController extends Controller
{
    public function index(): JsonResponse
    {
        $facilities = MapFacilities::query()
            //mapからブース詳細が見たい場合は追記する
            ->select(['id', 'store_id', 'name', 'type', 'floor', 'x', 'y'])
            ->get();

        return MapFacilityResource::collection($facilities)
            ->response()
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
