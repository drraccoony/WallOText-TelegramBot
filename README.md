# Wall-o-Text Bot 🤖

A Telegram bot that monitors chat messages and tracks when a specific user posts lengthy "wall of text" messages. The bot maintains a streak counter and publicly calls out when the streak is broken.

## Features

- **Streak Tracking**: Monitors days since the last wall of text
- **User-Specific**: Tracks messages from a designated target user
- **Length Detection**: Triggers on messages over 420 characters
- **Auto-Reply**: Responds with streak information when triggered
- **Webhook Security**: Validates requests using secret tokens
- **API Endpoint**: JSON endpoint to check current streak status

## Tech Stack

- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: PostgreSQL
- **Container**: Docker (Laravel Sail)
- **HTTP Client**: Guzzle

## Prerequisites

- Docker & Docker Compose
- A Telegram Bot Token (from [@BotFather](https://t.me/botfather))
- The target user's Telegram user ID
- A public URL for webhook (ngrok, server, etc.)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/drraccoony/WallOText-TelegramBot.git
   cd WallOText-TelegramBot
   ```

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Configure your `.env` file**
   ```env
   TELEGRAM_BOT_TOKEN=your_bot_token_here
   TELEGRAM_TARGET_USER_ID=target_user_id_here
   TELEGRAM_WEBHOOK_SECRET=your_random_secret_here
   ```

4. **Start the application with Sail**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Install dependencies**
   ```bash
   ./vendor/bin/sail composer install
   ```

6. **Generate application key**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

7. **Run migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

8. **Set up Telegram webhook**
   ```bash
   curl -X POST "https://api.telegram.org/bot<YOUR_BOT_TOKEN>/setWebhook" \
        -H "Content-Type: application/json" \
        -d '{
          "url": "https://your-domain.com/telegram/webhook",
          "secret_token": "your_random_secret_here"
        }'
   ```

## Configuration

### Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `TELEGRAM_BOT_TOKEN` | Your Telegram bot token from BotFather | `123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11` |
| `TELEGRAM_TARGET_USER_ID` | The Telegram user ID to monitor | `107339753` |
| `TELEGRAM_WEBHOOK_SECRET` | Secret token for webhook validation | `8UY6y5BqG6ZlBfa3fB9CWr2hdPMTZ1oM` |

### Getting User IDs

To find a Telegram user ID:
1. Add [@userinfobot](https://t.me/userinfobot) to your chat
2. The bot will display user IDs for all members

## How It Works

1. **Webhook Reception**: Telegram sends updates to `/telegram/webhook`
2. **Secret Validation**: Request is validated using `X-Telegram-Bot-Api-Secret-Token` header
3. **Message Processing**: Checks if message is from target user and exceeds 420 characters
4. **Streak Calculation**: Calculates days since last wall of text
5. **Response**: Bot replies with streak information
6. **Database Update**: Records timestamp of the wall of text

## API Endpoints

### `GET /`
Returns current streak status

**Response:**
```json
{
  "status": "online",
  "last_wall_timestamp": "2025-10-25T14:30:00+00:00",
  "last_wall_readable": "October 25, 2025 2:30 PM"
}
```

### `POST /telegram/webhook`
Receives Telegram webhook updates (for Telegram API use only)

## Database Schema

### `wall_streaks` Table
- `id`: Primary key
- `user_id`: Telegram user ID (string)
- `last_wall_at`: Timestamp of last wall of text
- `created_at`: Record creation timestamp
- `updated_at`: Record update timestamp

## Development

### Running Tests
```bash
./vendor/bin/sail artisan test
```

### Viewing Logs
```bash
./vendor/bin/sail artisan pail
```

### Database Access
```bash
./vendor/bin/sail psql
```

## Troubleshooting

### Bot Not Responding

1. **Check webhook is set correctly**
   ```bash
   curl "https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getWebhookInfo"
   ```

2. **Verify CSRF is disabled for webhook route**
   The webhook route should be excluded from CSRF protection (handled automatically in Laravel 12).

3. **Check logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify environment variables**
   ```bash
   ./vendor/bin/sail artisan config:cache
   ```

### Database Connection Issues

Ensure PostgreSQL container is running:
```bash
./vendor/bin/sail ps
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
