<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
Route::post('/register', 'App\Http\Controllers\Api\AuthController@register');
Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
Route::get('/user', 'App\Http\Controllers\Api\AuthController@user');

// User routes  
Route::get('/users', 'App\Http\Controllers\Api\UserController@index');
Route::get('/users/{id}', 'App\Http\Controllers\Api\UserController@show');

// Task routes
Route::get('/tasks', 'App\Http\Controllers\Api\TaskController@index');
Route::post('/tasks', 'App\Http\Controllers\Api\TaskController@store');
Route::get('/tasks/{id}', 'App\Http\Controllers\Api\TaskController@show');
Route::put('/tasks/{id}', 'App\Http\Controllers\Api\TaskController@update');
Route::delete('/tasks/{id}', 'App\Http\Controllers\Api\TaskController@destroy');

// Resource routes using apiResource (if needed)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('tasks', 'App\Http\Controllers\Api\TaskController');
// });