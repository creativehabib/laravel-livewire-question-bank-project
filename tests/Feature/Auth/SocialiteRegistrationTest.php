<?php

namespace Tests\Feature\Auth;

use App\Enums\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialiteRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('google_login_enabled', true);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_new_google_user_is_prompted_to_choose_role_before_registration(): void
    {
        $this->mockSocialiteUser('google-user@example.com', 'Google User');

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('social.register.show', ['provider' => 'google']));

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'google-user@example.com']);

        $pending = session()->get('socialite.registration');

        $this->assertIsArray($pending);
        $this->assertSame('google', $pending['provider']);
        $this->assertSame('google-user@example.com', $pending['email']);
        $this->assertSame('Google User', $pending['name']);
    }

    public function test_pending_social_registration_can_be_completed_as_teacher_with_additional_details(): void
    {
        $this->mockSocialiteUser('teacher@example.com', 'Teacher Example');

        $this->get('/auth/google/callback')
            ->assertRedirect(route('social.register.show', ['provider' => 'google']));

        $response = $this->post('/auth/google/register', [
            'role' => Role::TEACHER->value,
            'institution_name' => 'Example School',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'thana' => 'Dhanmondi',
            'phone' => '01700000000',
            'address' => 'House 1, Road 2, Dhaka',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $user = User::where('email', 'teacher@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals(Role::TEACHER, $user->role);
        $this->assertNotNull($user->role_confirmed_at);
        $this->assertNotNull($user->email_verified_at);
        $this->assertEquals('Example School', $user->institution_name);
        $this->assertEquals('Dhaka', $user->division);
        $this->assertEquals('Dhaka', $user->district);
        $this->assertEquals('Dhanmondi', $user->thana);
        $this->assertEquals('01700000000', $user->phone);
        $this->assertEquals('House 1, Road 2, Dhaka', $user->address);
        $this->assertNotNull($user->teacher_profile_completed_at);
        $this->assertNull(session()->get('socialite.registration'));
    }

    public function test_pending_social_registration_can_be_completed_as_student_without_teacher_details(): void
    {
        $this->mockSocialiteUser('student@example.com', 'Student Example');

        $this->get('/auth/google/callback')
            ->assertRedirect(route('social.register.show', ['provider' => 'google']));

        $response = $this->post('/auth/google/register', [
            'role' => Role::STUDENT->value,
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $user = User::where('email', 'student@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals(Role::STUDENT, $user->role);
        $this->assertNotNull($user->role_confirmed_at);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->institution_name);
        $this->assertNull($user->division);
        $this->assertNull($user->district);
        $this->assertNull($user->thana);
        $this->assertNull($user->phone);
        $this->assertNull($user->address);
        $this->assertNull($user->teacher_profile_completed_at);
        $this->assertNull(session()->get('socialite.registration'));
    }

    protected function mockSocialiteUser(string $email, string $name): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getName')->andReturn($name);
        $socialiteUser->shouldReceive('getNickname')->andReturn(null);
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.png');

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);
    }
}
