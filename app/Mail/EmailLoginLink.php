<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailLoginLink extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $url)
    {
    }

    public function build(): self
    {
        return $this
            ->subject(__('Your secure login link'))
            ->view('emails.auth.email-login-link', [
                'user' => $this->user,
                'url' => $this->url,
            ]);
    }
}
