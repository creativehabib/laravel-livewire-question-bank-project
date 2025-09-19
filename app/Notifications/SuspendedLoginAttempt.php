<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class SuspendedLoginAttempt extends Notification
{
    use Queueable;

    public function __construct(protected Carbon $suspendedUntil)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $timezone = config('app.timezone');
        $until = $this->suspendedUntil->copy()->timezone($timezone);
        $duration = now()->diffForHumans($this->suspendedUntil, true);

        return (new MailMessage)
            ->subject(__('Your account is currently suspended'))
            ->line(__('You attempted to log in while your account is suspended.'))
            ->line(__('You will regain access on :date.', [
                'date' => $until->toDayDateTimeString(),
            ]))
            ->line(__('That is approximately :duration from now.', [
                'duration' => $duration,
            ]));
    }

    public function toArray(object $notifiable): array
    {
        $timezone = config('app.timezone');

        return [
            'suspended_until' => $this->suspendedUntil->copy()->timezone($timezone)->toIso8601String(),
        ];
    }
}
