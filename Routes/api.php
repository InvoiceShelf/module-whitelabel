<?php

use Modules\WhiteLabel\Http\Controllers\UploadLogoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Signed-in company owners only: the logos change what every visitor of the
// admin and customer portals sees.
Route::middleware(['auth:sanctum', 'company'])->group(function () {
    Route::post('upload-logos', [UploadLogoController::class, 'uploadLogos']);
});
