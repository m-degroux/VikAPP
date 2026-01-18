<?php

use App\Http\Controllers\Web\ClubManagementController;
use App\Http\Controllers\Web\CreationController;
use App\Http\Controllers\Web\TeamController;
use App\Http\Controllers\Web\RaceManagmentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\RaidController;
use App\Http\Controllers\Web\WelcomeController;
use App\Http\Controllers\Web\RunnerController;
use App\Http\Controllers\Web\RaceController;
use App\Http\Controllers\Web\RaidManagmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/** * PUBLIC AREA
 */
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

/* USER DATA & DISCOVERY */
Route::resource('/raid', RaidController::class);
Route::resource('/race', RaceController::class);
Route::resource('/runner', RunnerController::class);

/* REGISTRATION & CREATION WORKFLOWS */
Route::get('/create/race', [CreationController::class, 'indexRace'])->name('create.race.index');
Route::post('/create/race/store', [CreationController::class, 'createRace'])->name('create.race.store');
Route::get('/create/club', [CreationController::class, 'indexClub'])->name('create.club.index');
Route::post('/create/club/store', [CreationController::class, 'createClub'])->name('create.club.store');
Route::get('/create/raid', [CreationController::class, 'indexRaid'])->name('create.race.index');
Route::get('create/team', [TeamController::class, 'form'])->name('create.team');
Route::post('create/team/store', [TeamController::class, 'register'])->name('create.team.store');

/* RACE INFORMATION */
Route::get('/race/{race_id}/info', [RaceController::class, 'info'])->name('race.info');

/** * MANAGEMENT AREA (Restricted Access)
 */
Route::prefix('manage')->name('manage.')->group(function () {
    // Club Management: accessible to Club Managers
    Route::resource('club', ClubManagementController::class);
    
    // Race Management: Import and overall management
    Route::resource('race', RaceManagmentController::class);
    Route::post('race/{id}/importCSV', [RaceManagmentController::class, 'importCsv'])->name('race.importCSV');

    // Raid Management: General event organization
    Route::resource('raid', RaidManagmentController::class);
});

/* ADMIN DASHBOARD */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth:admin', 'verified'])->name('dashboard');

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
            'route' => route('logs.delete', ['disk' => 'laravelLog', 'file' => 'laravel.log'])
        ]);
    } else {
        Log::debug("accessing log path : " . Storage::disk('log')->path("$file.log"));
        if (Storage::disk('log')->exists("$file.log")) {
            Log::debug("exists : OK");
            $content = Storage::disk('log')->get("$file.log");
            return view('log', [
                'file' => "$file.log",
                'content' => $content,
                'route' => null
            ]);
        } else {
            // Error handling for missing log files
            Log::debug("exists : KO");
            return "<h1>$file.log</h1><p style='color:red'>Not Found</p>";
        }
    }
});

// Runner Space: User dashboard for race results and history
Route::resource('/espace-coureur', RunnerController::class);

/* LOG CLEANUP */
Route::post('/logs/{disk}/{file}/delete', function (string $disk, string $file) {
    Storage::disk($disk)->delete($file);
    return Redirect::back();
})->name("logs.delete");

/** * AUTHENTICATION SYSTEM
 */
require __DIR__ . '/auth.php';

/* STATUTORY PAGES */
Route::get('/legal', function () {
    return view('pages/legal');
});