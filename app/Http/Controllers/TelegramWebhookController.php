<?php

// app/Http/Controllers/TelegramWebhookController.php
namespace App\Http\Controllers;

use App\Models\WallStreak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Verify webhook secret header from Telegram
        $secret = $request->header('X-Telegram-Bot-Api-Secret-Token');
        if (!hash_equals(config('telegram.webhook_secret'), (string)$secret)) {
            return response()->json(['ok' => false, 'error' => 'bad secret'], 403);
        }

        $update = $request->json()->all();
        $message = $update['message'] ?? $update['edited_message'] ?? null;
        if (!$message) {
            return ['ok' => true]; // ignore non-message updates
        }

        $groupId = (int) config('telegram.group_chat_id');
        $targetUserId = (int) config('telegram.target_user_id');

        // Only care about the configured group
        $chatId = $message['chat']['id'] ?? null;
        if ((int)$chatId !== $groupId) {
            return ['ok' => true];
        }

        // Extract sender + text length (handles text or caption)
        $fromId = (int) ($message['from']['id'] ?? 0);
        $text = $message['text'] ?? $message['caption'] ?? '';
        $len = mb_strlen($text);

        // Trigger only if it's the target user and > 420 chars
        if ($fromId === $targetUserId && $len > 420) {
            $streak = WallStreak::firstOrCreate(['user_id' => (string)$targetUserId]);

            $last = $streak->last_wall_at;
            $days = $last ? $last->diffInDays(now()) : 0;

            // Send the announcement
            $botToken = config('telegram.bot_token');
            $payload = [
                'chat_id' => $groupId,
                'text' => "Its been 0 days since Kal'hona posted a wall of text. Ending the streak of {$days} days",
                'reply_to_message_id' => $message['message_id'] ?? null,
                'allow_sending_without_reply' => true,
            ];
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", $payload)->throw();

            // Reset the streak timestamp
            $streak->forceFill(['last_wall_at' => now()])->save();
        }

        return ['ok' => true];
    }
}
