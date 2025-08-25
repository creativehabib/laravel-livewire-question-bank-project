<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Events\UserTyping;
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
    public $lastMessageKey;
    public $typing;

    public function mount(): void
    {
        $chat = Chat::firstOrCreate(['user_id' => Auth::id()]);
        $this->assignedAdminId = $chat->assigned_admin_id;
        $last = $this->messages->last();
        $this->lastMessageKey = $last ? ($last->id ?? $last->created_at->timestamp) : null;
    }

    public function getListeners(): array
    {
        $userId = Auth::id();
        return [
            "echo-private:chat-assigned.{$userId},ChatAssigned" => 'setAdmin',
            "echo-private:chat.{$userId},MessageSent" => '$refresh',
            "echo-private:chat.{$userId},UserTyping" => 'showTyping',
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
            $this->markAsSeen();
        }
    }

    public function updatedMessage(): void
    {
        if ($recipient = $this->getAdminId()) {
            broadcast(new UserTyping(Auth::id(), $recipient))->toOthers();
        }
    }

    public function send(): void
    {
        $this->validate();

        $messages = Cache::get('chat.pending', []);
        $payload = [
            'user_id' => Auth::id(),
            'recipient_id' => $this->getAdminId(),
            'message' => $this->message,
            'created_at' => now(),
        ];

        $messages[] = $payload;
        Cache::put('chat.pending', $messages, now()->addMinutes(1));

        broadcast(new MessageSent($payload))->toOthers();

        $this->message = '';
        $this->dispatch('chat-message-sent');
    }
    
    protected function markAsDelivered(): void
    {
        $adminId = $this->getAdminId();
        if (!$adminId) {
            return;
        }

        ChatMessage::where('user_id', $adminId)
            ->where('recipient_id', Auth::id())
            ->whereNull('delivered_at')
            ->update(['delivered_at' => now()]);

        $pending = Cache::get('chat.pending', []);
        foreach ($pending as &$msg) {
            if ($msg['user_id'] == $adminId && $msg['recipient_id'] == Auth::id() && empty($msg['delivered_at'])) {
                $msg['delivered_at'] = now();
            }
        }
        unset($msg);
        Cache::put('chat.pending', $pending, now()->addMinutes(1));
    }

    protected function markAsSeen(): void
    {
        $adminId = $this->getAdminId();
        if (!$adminId) {
            return;
        }

        ChatMessage::where('user_id', $adminId)
            ->where('recipient_id', Auth::id())
            ->whereNull('seen_at')
            ->update(['delivered_at' => now(), 'seen_at' => now()]);

        $pending = Cache::get('chat.pending', []);
        foreach ($pending as &$msg) {
            if ($msg['user_id'] == $adminId && $msg['recipient_id'] == Auth::id()) {
                $msg['delivered_at'] = $msg['delivered_at'] ?? now();
                $msg['seen_at'] = now();
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
                $msg['delivered_at'] = isset($msg['delivered_at']) ? \Illuminate\Support\Carbon::parse($msg['delivered_at']) : null;
                $msg['seen_at'] = isset($msg['seen_at']) ? \Illuminate\Support\Carbon::parse($msg['seen_at']) : null;
                return (object) $msg;
            });

        return $messages->toBase()->merge($cached)->sortBy('created_at')->values();
    }

    public function getUnreadCountProperty(): int
    {
        $adminId = $this->getAdminId();

        $dbCount = ChatMessage::where('user_id', $adminId)
            ->where('recipient_id', Auth::id())
            ->whereNull('seen_at')
            ->count();

        $cachedCount = 0;
        foreach (Cache::get('chat.pending', []) as $msg) {
            if ($msg['user_id'] == $adminId && $msg['recipient_id'] == Auth::id() && empty($msg['seen_at'])) {
                $cachedCount++;
            }
        }

        return $dbCount + $cachedCount;
    }

    public function render()
    {
        if ($this->assignedAdminId) {
            $this->markAsDelivered();
            if ($this->open) {
                $this->markAsSeen();
            }
        }

        $messages = $this->messages;
        $last = $messages->last();
        $key = $last ? ($last->id ?? $last->created_at->timestamp) : null;

        if ($last && $key !== $this->lastMessageKey && $last->user_id !== Auth::id()) {
            $this->dispatch('chat-message-received');
        }
        $this->lastMessageKey = $key;

        $admin = $this->assignedAdminId ? User::find($this->assignedAdminId) : null;
        $chatTitle = $admin ? 'Chat with ' . $admin->name : 'Chat with Support Team';

        return view('livewire.chat-popup', [
            'messages' => $messages,
            'unreadCount' => $this->unreadCount,
            'chatTitle' => $chatTitle,
        ]);
    }

    public function showTyping(): void
    {
        $this->typing = now();
    }

    public function getIsTypingProperty(): bool
    {
        return $this->typing && now()->diffInSeconds($this->typing) < 5;
    }
}

