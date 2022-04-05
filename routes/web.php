<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UploadImageController;

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/single/{post}', [SingleController::class,'single'])->name('single');
Route::post('/single/{post}/comment', [SingleController::class,'comment'])
->name('single.comment')->middleware('auth:web');

Route::prefix('admin')->middleware('admin')->group(function(){
    Route::resource('post', PostController::class)->except(['show']);

    Route::post('/upload',[UploadImageController::class,'upload'])->name('upload');
});



Auth::routes();

