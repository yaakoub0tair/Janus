<?php

use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatsController;


Route::post('register', [UserController::class, "create"]);
Route::post('login', [UserController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    Route::post(
        "logout",
        [UserController::class, "logout"]
    );
    Route::apiResource("habits", HabitController::class);
    // Route::apiResource("habitsLog",HabitLogController::class);
    Route::post("habitsLog", [HabitLogController::class, "store"]);
    Route::get("habitsLog/{habit_id}", [HabitLogController::class, 'show']);
    // Route::get('/habits/{id}/stats', [HabitController::class, 'stats']);
    Route::delete('habits/{id}/logs/{logId}',[HabitLogController::class,'destroy']);

    Route::get('habits/{id}/stats', [StatsController::class, 'show']);
    Route::get('stats/overview',    [StatsController::class, 'overview']);
});
