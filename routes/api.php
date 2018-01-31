<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('list.cards', 'Api\CardListItemController');

Route::get('vendor/{vendor}/lookup/{card}', function (App\VendorSite $vendor, App\CardListItem $card) {
    dispatch(new App\Jobs\BuyPriceLookup($card, $vendor));
});
