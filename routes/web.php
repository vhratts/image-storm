<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ImageEditorController;
use App\Livewire\Pages\ApplicationHome;
use Illuminate\Support\Facades\Route;

Route::get("/", ApplicationHome::class)->name("Application-home");
Route::get('/assets/{any}', [ApplicationController::class, 'ApiAssetController'])->where('any', '.*');;
Route::prefix("/api")->group(function(){
    Route::post('/image-editor', [ImageEditorController::class, 'editImage']);
});