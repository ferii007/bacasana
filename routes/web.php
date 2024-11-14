<?php

use Illuminate\Support\Facades\Route;

Route::get('/','\App\Http\Controllers\IndexController@index')->name('home');

Route::get('/about', function () {
    return view('about/index');
})->name('about');

Route::get('/contact', function () {
    return view('contact/index');
})->name('contact');


Route::prefix('blog')->group(function () {
    Route::get('/','\App\Http\Controllers\BlogController@index')->name('blog');
    Route::get('/{blog}','\App\Http\Controllers\BlogController@blogPost')->name('blogPost');
});