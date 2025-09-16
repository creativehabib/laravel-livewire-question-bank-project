<?php

namespace Tests\Feature\Auth;

use App\Enums\Role;
use App\Mail\EmailRegistrationLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    public function test_new_users_can_register(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');

        $component->assertRedirect(route('verification.notice', absolute: false));

        $this->assertAuthenticated();

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNull($user->role_confirmed_at);
        $this->assertEquals(Role::STUDENT, $user->role);
    }

    public function test_email_registration_flow_creates_user(): void
    {
        Mail::fake();

        $component = Volt::test('pages.auth.register')
            ->set('emailRegistrationName', 'Email User')
            ->set('emailRegistrationEmail', 'email-user@example.com')
            ->set('emailRegistrationRole', Role::TEACHER->value);

        $component->call('sendRegistrationLink');

        $component->assertHasNoErrors();

        $signedUrl = null;

        Mail::assertSent(EmailRegistrationLink::class, function (EmailRegistrationLink $mail) use (&$signedUrl) {
            $this->assertEquals('Email User', $mail->name);
            $this->assertEquals(Role::TEACHER, $mail->role);
            $signedUrl = $mail->url;

            return true;
        });

        $this->assertNotEmpty($signedUrl);

        $response = $this->get($signedUrl);

        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $user = User::where('email', 'email-user@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals(Role::TEACHER, $user->role);
        $this->assertNotNull($user->role_confirmed_at);
        $this->assertNotNull($user->email_verified_at);
    }
}
