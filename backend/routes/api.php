<?php

use App\Http\Controllers\Api\V1\Map\FacilityController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(
        ['message' => 'API動作確認OK'],
        Response::HTTP_OK,
        [],
        JSON_UNESCAPED_UNICODE
    );
});

Route::prefix('v1')->group(function () {
    Route::get('map/facilities', [FacilityController::class, 'index']);
});
