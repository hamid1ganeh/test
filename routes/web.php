<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;


Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/single/{post}', [SingleController::class,'single'])->name('single');
Route::post('/single/{post}/comment', [SingleController::class,'comment'])
->name('single.comment')->middleware('auth:web');

Auth::routes();

