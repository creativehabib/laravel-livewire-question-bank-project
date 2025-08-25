<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public $chat_retention_value;
    public $chat_retention_unit = 'days';
    public $chat_message_max_length;

    protected $rules = [
        'chat_retention_value' => 'required|integer|min:1',
        'chat_retention_unit' => 'required|in:hours,days',
        'chat_message_max_length' => 'required|integer|min:1',
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
    }

    public function save(): void
    {
        $this->validate();
        $hours = $this->chat_retention_value * ($this->chat_retention_unit === 'days' ? 24 : 1);
        Setting::set('chat_retention_hours', $hours);
        Setting::set('chat_message_max_length', $this->chat_message_max_length);
        session()->flash('status', 'Settings updated');
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}

