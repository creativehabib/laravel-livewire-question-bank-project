<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Livewire\Admin\Settings as SettingsComponent;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ChatToneSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_chat_tone_settings(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $this->actingAs($admin);

        $file = UploadedFile::fake()->create('tone.mp3', 10, 'audio/mpeg');

        Livewire::test(SettingsComponent::class)
            ->set('chat_tone_enabled', false)
            ->set('chat_tone', $file)
            ->call('save');

        $this->assertSame('0', Setting::get('chat_tone_enabled'));
        $path = Setting::get('chat_message_tone');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }
}

