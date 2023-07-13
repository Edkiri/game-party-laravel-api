<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartyController;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::post('party/create', [PartyController::class, 'create'])->middleware('auth:sanctum');
Route::post('party/{partyId}/join', [PartyController::class, 'join'])->middleware('auth:sanctum');
Route::get('party/{partyId}/members', [PartyController::class, 'getMembers']);