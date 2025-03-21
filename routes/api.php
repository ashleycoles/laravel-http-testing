<?php

use App\Http\Controllers\PostApiController;
use App\Http\Controllers\ProductApiController;
use Illuminate\Support\Facades\Route;

// All routes added to api.php automatically start with /api/
Route::get('/posts', [PostApiController::class, 'all']); // /api/posts
Route::get('/posts/{post}', [PostApiController::class, 'find']);
Route::post('/posts', [PostApiController::class, 'create']);

Route::get('/products', [ProductApiController::class, 'all']);
Route::get('/products/{product}', [ProductApiController::class, 'find']);
Route::post('/products', [ProductApiController::class, 'create']);
Route::patch('/products/{product}', [ProductApiController::class, 'update']);
Route::delete('/products/{product}', [ProductApiController::class, 'delete']);
