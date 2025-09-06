<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use DateTimeZone;

class Settings extends Component
{
    public $chat_retention_value;
    public $chat_retention_unit = 'days';
    public $chat_message_max_length;
    public $chat_daily_message_limit;
    public $timezone;
    public array $timezones = [];
    public $chat_ai_enabled = false;
    public $openai_api_key = '';

    protected $rules = [
        'chat_retention_value' => 'required|integer|min:1',
        'chat_retention_unit' => 'required|in:hours,days',
        'chat_message_max_length' => 'required|integer|min:1',
        'chat_daily_message_limit' => 'required|integer|min:1',
        'timezone' => 'required|timezone',
        'chat_ai_enabled' => 'boolean',
        'openai_api_key' => 'nullable|string',
    ];

    public function mount(): void
    {
        $hours = Setting::get('chat_retention_hours', config('chat.retention_hours'));
        if ($hours % 24 === 0) {
            $this->chat_retention_unit = 'days';
            $this->chat_retention_value = $hours / 24;
        } else {
            $this->chat_retention_unit = 'hours';
            $this->chat_retention_value = $hours;
        }

        $this->chat_message_max_length = Setting::get('chat_message_max_length', config('chat.message_max_length'));
        $this->chat_daily_message_limit = Setting::get('chat_daily_message_limit', config('chat.daily_message_limit'));
        $this->timezone = Setting::get('timezone', config('app.timezone'));
        $this->timezones = DateTimeZone::listIdentifiers();
        $this->chat_ai_enabled = (bool) Setting::get('chat_ai_enabled', false);
        $this->openai_api_key = Setting::get('openai_api_key', '');
    }

    public function save(): void
    {
        $this->validate();
        $hours = $this->chat_retention_value * ($this->chat_retention_unit === 'days' ? 24 : 1);
        Setting::set('chat_retention_hours', $hours);
        Setting::set('chat_message_max_length', $this->chat_message_max_length);
        Setting::set('chat_daily_message_limit', $this->chat_daily_message_limit);
        Setting::set('timezone', $this->timezone);
        Setting::set('chat_ai_enabled', $this->chat_ai_enabled ? 1 : 0);
        Setting::set('openai_api_key', $this->openai_api_key);
        config(['app.timezone' => $this->timezone]);
        date_default_timezone_set($this->timezone);
        session()->flash('status', 'Settings updated');
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}

