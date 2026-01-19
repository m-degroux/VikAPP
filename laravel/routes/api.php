<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\RaceController;
use App\Http\Controllers\Api\RaidController;
use App\Http\Controllers\Api\RegisterRaceController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });
        Route::put('/profile', [AuthController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AuthController::class, 'destroy'])->name('profile.destroy');
    });

    Route::apiResource('raids', RaidController::class)->names([
        'index' => 'api.raids.index',
        'store' => 'api.raids.store',
        'show' => 'api.raids.show',
        'update' => 'api.raids.update',
        'destroy' => 'api.raids.destroy',
    ]);
    Route::apiResource('races', RaceController::class)->names([
        'index' => 'api.races.index',
        'store' => 'api.races.store',
        'show' => 'api.races.show',
        'update' => 'api.races.update',
        'destroy' => 'api.races.destroy',
    ]);
    Route::apiResource('clubs', ClubController::class)->names([
        'index' => 'api.clubs.index',
        'store' => 'api.clubs.store',
        'show' => 'api.clubs.show',
        'update' => 'api.clubs.update',
        'destroy' => 'api.clubs.destroy',
    ]);
    Route::apiResource('teams', TeamController::class)->names([
        'index' => 'api.teams.index',
        'store' => 'api.teams.store',
        'show' => 'api.teams.show',
        'update' => 'api.teams.update',
        'destroy' => 'api.teams.destroy',
    ]);

    Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.members.destroy');

    Route::post('/races/register', [RegisterRaceController::class, 'store'])->name('races.register');
});
