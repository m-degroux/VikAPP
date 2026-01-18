<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\JoinTeamController;
use App\Http\Controllers\Api\RaceController;
use App\Http\Controllers\Api\RaidController;
use App\Http\Controllers\Api\RegisterRaceController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/raid', [RaidController::class, 'index']);
Route::get('/raid/{id}', [RaidController::class, 'show']);

Route::get('/race', [RaceController::class, 'index']);
Route::get('/race/{id}', [RaceController::class, 'show']);

Route::get('clubs', [ClubController::class, 'index']);
Route::get('clubs/{id}', [ClubController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/teams/join', [TeamController::class, 'join'])->name('teams.join');

    Route::post('/team', [JoinTeamController::class, 'store']);
    Route::get('/team/{teamId}', [JoinTeamController::class, 'showByTeam']);
    Route::delete('/team/team/{teamId}/user/{userId}', [JoinTeamController::class, 'destroy']);

    Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
    Route::put('/clubs/{id}', [ClubController::class, 'update'])->name('clubs.update');

    Route::post('/races/register', [RegisterRaceController::class, 'store'])->name('races.register');

    Route::post('/raids', [RaidController::class, 'store'])->name('raids.store');

    Route::post('/race', [RaceController::class, 'store'])->name('races.store');

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/profile', [AuthController::class, 'update']);
    Route::delete('/user/profile', [AuthController::class, 'destroy']);
});
