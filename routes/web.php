<?php

use App\Http\Controllers\ImageEditorController;
use App\Livewire\Pages\ApplicationHome;
use Illuminate\Support\Facades\Route;

Route::get("/", ApplicationHome::class)->name("Application-home");
Route::prefix("/api")->group(function(){
    Route::post('/image-editor', [ImageEditorController::class, 'editImage']);
});