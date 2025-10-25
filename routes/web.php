<?php

use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;
use App\Models\WallStreak;

Route::get('/', function () {
    $streak = WallStreak::first(); // assuming only 1 target user is tracked

    return response()->json([
        'status' => 'online',
        'last_wall_timestamp' => $streak?->last_wall_at?->toIso8601String(),
        'last_wall_readable' => $streak?->last_wall_at
            ? $streak->last_wall_at->format('F j, Y g:i A')
            : null,
    ]);
});

// Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
