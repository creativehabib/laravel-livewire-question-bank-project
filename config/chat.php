<?php

return [
    // Number of hours chat messages are retained before being pruned
    'retention_hours' => env('CHAT_RETENTION_HOURS', 30 * 24),

    // Maximum length of a single chat message
    'message_max_length' => env('CHAT_MESSAGE_MAX_LENGTH', 500),
];

