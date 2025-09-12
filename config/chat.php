<?php

return [
    // Number of hours chat messages are retained before being pruned
    'retention_hours' => env('CHAT_RETENTION_HOURS', 30 * 24),

    // Maximum length of a single chat message
    'message_max_length' => env('CHAT_MESSAGE_MAX_LENGTH', 500),

    // Maximum number of chat messages a user can send per day
    'daily_message_limit' => env('CHAT_DAILY_MESSAGE_LIMIT', 100),

    // Minutes an admin must be inactive before AI responses are triggered
    'ai_admin_offline_minutes' => env('CHAT_AI_ADMIN_OFFLINE_MINUTES', 5),

    // Whether chat message tones are enabled by default
    'tone_enabled' => env('CHAT_TONE_ENABLED', true),

    // Default path for the chat message tone. If null, a basic beep is used.
    'message_tone' => env('CHAT_MESSAGE_TONE'),
];

