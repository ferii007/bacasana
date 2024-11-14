<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('admin-dashboard-bacasana')->group(function () {
    Route::get('/getAllPosts', '\App\Http\Controllers\apiController\PostController@getAllPosts')->name('getAllPosts');
    Route::get('/getAllCategories', '\App\Http\Controllers\apiController\CategoriesController@getAllCategories')->name('getAllCategories');
    Route::get('/fetchAllPosts', '\App\Http\Controllers\apiController\PostController@fetchAllPosts')->name('fetchAllPosts');
    Route::get('/fetchAllCategories', '\App\Http\Controllers\apiController\CategoriesController@fetchAllCategories')->name('fetchAllCategories');

    Route::post('addPost', '\App\Http\Controllers\apiController\PostController@addPost')->name('addPost');
    Route::post('addCategory', '\App\Http\Controllers\apiController\CategoriesController@addCategory')->name('addCategory');
});