<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ChatPopup extends Component
{
    public $message = '';
    public $open = false;
    public $assignedAdminId;

    public function mount(): void
    {
        $chat = Chat::firstOrCreate(['user_id' => Auth::id()]);
        $this->assignedAdminId = $chat->assigned_admin_id;
    }

    public function getListeners(): array
    {
        $userId = Auth::id();
        return [
            "echo-private:chat-assigned.{$userId},ChatAssigned" => 'setAdmin',
        ];
    }

    protected function rules(): array
    {
        return [
            'message' => 'required|string|max:' . config('chat.message_max_length'),
        ];
    }

    protected function getAdminId(): ?int
    {
        if (!$this->assignedAdminId) {
            $chat = Chat::where('user_id', Auth::id())->first();
            if ($chat) {
                $this->assignedAdminId = $chat->assigned_admin_id;
            }
        }
        return $this->assignedAdminId;
    }

    public function setAdmin($event): void
    {
        $this->assignedAdminId = $event['admin_id'];
    }

    public function updatedOpen($value): void
    {
        if ($value) {
            $this->markAsRead();
        }
    }

    public function send(): void
    {
        $this->validate();

        $messages = Cache::get('chat.pending', []);
        $messages[] = [
            'user_id' => Auth::id(),
            'recipient_id' => $this->getAdminId(),
            'message' => $this->message,
            'created_at' => now(),
        ];

        Cache::put('chat.pending', $messages, now()->addMinutes(1));

        $this->message = '';
        $this->dispatch('chat-message-sent');
    }

    protected function markAsRead(): void
    {
        $adminId = $this->getAdminId();

        ChatMessage::where('user_id', $adminId)
            ->where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $pending = Cache::get('chat.pending', []);
        foreach ($pending as &$msg) {
            if ($msg['user_id'] == $adminId && $msg['recipient_id'] == Auth::id()) {
                $msg['read_at'] = now();
            }
        }
        unset($msg);
        Cache::put('chat.pending', $pending, now()->addMinutes(1));
    }

    public function getMessagesProperty()
    {
        $adminId = $this->getAdminId();

        $messages = ChatMessage::with('user')
            ->where(function ($query) use ($adminId) {
                $query->where('user_id', Auth::id())
                    ->where('recipient_id', $adminId);
            })
            ->orWhere(function ($query) use ($adminId) {
                $query->where('user_id', $adminId)
                    ->where('recipient_id', Auth::id());
            })
            ->latest()
            ->take(20)
            ->get()
            ->reverse();

        $cached = collect(Cache::get('chat.pending', []))
            ->filter(function ($msg) use ($adminId) {
                return ($msg['user_id'] === Auth::id() && $msg['recipient_id'] == $adminId)
                    || ($msg['user_id'] == $adminId && $msg['recipient_id'] == Auth::id());
            })
            ->map(function ($msg) {
                $msg['user'] = User::find($msg['user_id']);
                $msg['created_at'] = \Illuminate\Support\Carbon::parse($msg['created_at']);
                return (object) $msg;
            });

        return $messages->toBase()->merge($cached)->sortBy('created_at')->values();
    }

    public function getUnreadCountProperty(): int
    {
        $adminId = $this->getAdminId();

        $dbCount = ChatMessage::where('user_id', $adminId)
            ->where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        $cachedCount = 0;
        foreach (Cache::get('chat.pending', []) as $msg) {
            if ($msg['user_id'] == $adminId && $msg['recipient_id'] == Auth::id() && empty($msg['read_at'])) {
                $cachedCount++;
            }
        }

        return $dbCount + $cachedCount;
    }

    public function render()
    {
        if ($this->open) {
            $this->markAsRead();
        }

        $messages = $this->messages;
        $admin = $this->assignedAdminId ? User::find($this->assignedAdminId) : null;
        $chatTitle = $admin ? 'Chat with ' . $admin->name : 'Chat with Support Team';

        return view('livewire.chat-popup', [
            'messages' => $messages,
            'unreadCount' => $this->unreadCount,
            'chatTitle' => $chatTitle,
        ]);
    }
}

