<?php

use App\Http\Controllers\Web\ClubCreationController;
use App\Http\Controllers\Web\ClubManagementController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\RaceController;
use App\Http\Controllers\Web\RaceCreationController;
use App\Http\Controllers\Web\RaceManagmentController;
use App\Http\Controllers\Web\RaceResultController;
use App\Http\Controllers\Web\RaidController;
use App\Http\Controllers\Web\RaidCreationController;
use App\Http\Controllers\Web\RaidManagmentController;
use App\Http\Controllers\Web\RunnerController;
use App\Http\Controllers\Web\TeamCreationController;
use App\Http\Controllers\Web\WelcomeController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/** * PUBLIC AREA */
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/* USER DATA & DISCOVERY */
/* REGISTRATION & CREATION WORKFLOWS - Must be before resource routes */
Route::middleware('auth')->group(function () {
    Route::get('raids/create', [RaidCreationController::class, 'create'])->name('raids.create');
    Route::post('raids/store', [RaidController::class, 'store'])->name('raids.store');
    
    Route::get('races/create', [RaceCreationController::class, 'create'])->name('races.create');
    Route::post('races/store', [RaceCreationController::class, 'store'])->name('races.store');
    
    Route::get('clubs/create', [ClubCreationController::class, 'create'])->name('clubs.create');
    Route::post('clubs/store', [ClubCreationController::class, 'store'])->name('clubs.store');
    
    Route::get('teams/create', [TeamCreationController::class, 'create'])->name('teams.create');
    Route::post('teams/store', [TeamCreationController::class, 'store'])->name('teams.store');
});

Route::resource('raids', RaidController::class)->only(['index', 'show'])->names([
    'index' => 'raid.index',
    'show' => 'raid.show',
]);
Route::resource('races', RaceController::class)->only(['index', 'show']);
Route::resource('runners', RunnerController::class)->only(['index', 'show']);

/** * MANAGEMENT AREA (Restricted Access)
 */
Route::prefix('manage')->name('manage.')->middleware('auth:web,admin')->group(function () {
    // Club Management: accessible to Club Managers
    Route::resource('clubs', ClubManagementController::class)->except(['show']);

    // Race Results
    Route::get('/races/{race_id}/results', [RaceResultController::class, 'index'])->name('races.results');

    // Race Management: Import and overall management
    Route::resource('races', RaceManagmentController::class);
    Route::post('races/{id}/importCSV', [RaceManagmentController::class, 'importCsv'])->name('races.importCSV');

    // Raid Management: General event organization
    Route::resource('raids', RaidManagmentController::class);
});

/* ADMIN DASHBOARD */
Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth:admin', 'verified'])
    ->name('dashboard');

/* USER PROFILE MANAGEMENT (requires web authentication) */
Route::middleware('auth:web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/** * SYSTEM UTILITIES (Logging & Debugging)
 */
Route::get('/logs/{file}', function (string $file) {
    if ($file === 'laravel') {
        // Retrieve standard Laravel framework logs
        $content = Storage::disk('laravelLog')->get('laravel.log');

        return view('log', [
            'file' => 'laravel.log',
            'content' => $content,
            'route' => route('logs.delete', ['disk' => 'laravelLog', 'file' => 'laravel.log']),
        ]);
    } else {
        Log::debug('accessing log path : '.Storage::disk('log')->path("$file.log"));
        if (Storage::disk('log')->exists("$file.log")) {
            Log::debug('exists : OK');
            $content = Storage::disk('log')->get("$file.log");

            return view('log', [
                'file' => "$file.log",
                'content' => $content,
                'route' => null,
            ]);
        } else {
            // Error handling for missing log files
            Log::debug('exists : KO');

            return "<h1>$file.log</h1><p style='color:red'>Not Found</p>";
        }
    }
});

// Runner Space: User dashboard for race results and history
Route::resource('/espace-coureur', RunnerController::class)->names([
    'index' => 'runner.index',
    'show' => 'runner.show',
    'create' => 'runner.create',
    'store' => 'runner.store',
    'edit' => 'runner.edit',
    'update' => 'runner.update',
    'destroy' => 'runner.destroy',
]);

/* LOG CLEANUP */
Route::post('/logs/{disk}/{file}/delete', function (string $disk, string $file) {
    Storage::disk($disk)->delete($file);

    return Redirect::back();
})->name('logs.delete');

/** * AUTHENTICATION SYSTEM
 */
require __DIR__.'/auth.php';

/* STATUTORY PAGES */
Route::get('/legal', function () {
    return view('public.legal');
})->name('legal');

Route::get('/test', function () {
    return view('test');
});
