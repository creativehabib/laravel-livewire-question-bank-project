<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat-assigned.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat-admins', function ($user) {
    return $user->isAdmin();
});

