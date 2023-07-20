<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\UserController;

// Auth
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// User
Route::get('user/my-profile', [UserController::class, 'getProfile'])->middleware('auth:sanctum');
Route::put('user', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');

// Party
Route::get('party', [PartyController::class, 'listParties']);
Route::post('party/create', [PartyController::class, 'create'])->middleware('auth:sanctum');

Route::post('party/{partyId}/join', [PartyController::class, 'join'])->middleware('auth:sanctum');
Route::post('party/{partyId}/exit', [PartyController::class, 'exit'])->middleware(['auth:sanctum', 'is-member']);

Route::get('party/{partyId}/members', [PartyController::class, 'getMembers'])->middleware(['auth:sanctum', 'is-member']);

Route::post('party/{partyId}/create-message', [MessageController::class, 'create'])->middleware(['auth:sanctum', 'is-member']);
