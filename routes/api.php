<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramWebhookController;

Route::get('/status', function () {
    return response()->json(['status' => 'online']);
});

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
 