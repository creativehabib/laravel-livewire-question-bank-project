<?php

namespace App\Livewire;

use App\Events\UserTyping;
use App\Jobs\SendChatMessage;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
            "echo-private:chat.{$userId},ChatMessageSent" => '$refresh',
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

        if (!Auth::user()->isAdmin()) {
            $limit = Setting::get('chat_daily_message_limit', config('chat.daily_message_limit'));
            $count = ChatMessage::where('user_id', Auth::id())
                ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                ->count();
            if ($count >= $limit) {
                $this->addError('message', "You can send {$limit} messages per day. Your limit has been reached.");
                return;
            }
        }

        SendChatMessage::dispatchSync([
            'user_id' => Auth::id(),
            'recipient_id' => $this->getAdminId(),
            'message' => $this->message,
            'created_at' => now(),
        ]);

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
    }

    public function getMessagesProperty()
    {
        $adminId = $this->getAdminId();

        return ChatMessage::with('user')
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
    }

    public function getUnreadCountProperty(): int
    {
        $adminId = $this->getAdminId();

        return ChatMessage::where('user_id', $adminId)
            ->where('recipient_id', Auth::id())
            ->whereNull('seen_at')
            ->count();
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

