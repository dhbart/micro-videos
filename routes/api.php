<?php

use App\Http\Controllers\CategoryController;
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

Route::group(['namespace' => 'Api'], function () {
    $exceptCreateAndEdit = ['except' => ['create','edit']];
    
    Route::resource('categories', 'CategoryController', $exceptCreateAndEdit);
    Route::resource('genres', 'GenreController', $exceptCreateAndEdit);
    Route::resource('cast_members', 'CastMemberController', $exceptCreateAndEdit);
    Route::resource('videos', 'VideoController', $exceptCreateAndEdit);
});