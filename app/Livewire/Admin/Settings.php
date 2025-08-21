<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public $chat_retention_days;

    protected $rules = [
        'chat_retention_days' => 'required|integer|min:1',
    ];

    public function mount(): void
    {
        $this->chat_retention_days = Setting::get('chat_retention_days', config('chat.retention_days'));
    }

    public function save(): void
    {
        $this->validate();
        Setting::set('chat_retention_days', $this->chat_retention_days);
        session()->flash('status', 'Settings updated');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}

