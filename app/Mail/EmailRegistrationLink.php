<?php

namespace App\Mail;

use App\Enums\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailRegistrationLink extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public Role $role,
        public string $url
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject(__('Complete your registration'))
            ->view('emails.auth.email-registration-link', [
                'name' => $this->name,
                'role' => $this->role,
                'url' => $this->url,
            ]);
    }
}
