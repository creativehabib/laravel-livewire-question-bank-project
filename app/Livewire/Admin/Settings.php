<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public $chat_retention_days;
    public $chat_message_max_length;

    protected $rules = [
        'chat_retention_days' => 'required|integer|min:1',
        'chat_message_max_length' => 'required|integer|min:1',
    ];

    public function mount(): void
    {
        $this->chat_retention_days = Setting::get('chat_retention_days', config('chat.retention_days'));
        $this->chat_message_max_length = Setting::get('chat_message_max_length', config('chat.message_max_length'));
    }

    public function save(): void
    {
        $this->validate();
        Setting::set('chat_retention_days', $this->chat_retention_days);
        Setting::set('chat_message_max_length', $this->chat_message_max_length);
        session()->flash('status', 'Settings updated');
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}

