<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TelegramConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config([
            'telegram.bot_token'     => env('TELEGRAM_BOT_TOKEN'),
            'telegram.group_chat_id' => env('TELEGRAM_GROUP_CHAT_ID'),
            'telegram.target_user_id'=> env('TELEGRAM_TARGET_USER_ID'),
            'telegram.webhook_secret'=> env('TELEGRAM_WEBHOOK_SECRET'),
        ]);
    }
    public function boot(): void {}
}

