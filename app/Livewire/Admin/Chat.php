<?php

namespace App\Livewire\Admin;

use App\Events\ChatAssigned;
use App\Events\UserTyping;
use App\Jobs\SendChatMessage;
use App\Models\Chat as ChatModel;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Chat extends Component
{
    public $message = '';
    public $recipient_id = '';
    public $lastMessageKey;
    public $typing;

    public function getListeners(): array
    {
        $userId = Auth::id();
        return [
            "echo-private:chat.{$userId},ChatMessageSent" => '$refresh',
            "echo-private:chat.{$userId},UserTyping" => 'showTyping',
        ];
    }

    protected function rules(): array
    {
        $max = Setting::get('chat_message_max_length', config('chat.message_max_length'));

        return [
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:' . $max,
        ];
    }

    public function updatedRecipientId($value): void
    {
        $chat = ChatModel::firstOrCreate(['user_id' => $value]);
        if ($chat->assigned_admin_id !== Auth::id()) {
            $chat->assigned_admin_id = Auth::id();
            $chat->save();

        ChatMessage::where('user_id', $value)
            ->whereNull('recipient_id')
            ->update(['recipient_id' => Auth::id()]);

            event(new ChatAssigned($chat));
        }

        $this->lastMessageKey = null;
    }

    public function updatedMessage(): void
    {
        if ($this->recipient_id) {
            broadcast(new UserTyping(Auth::id(), $this->recipient_id))->toOthers();
        }
    }

    public function send()
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
            'recipient_id' => $this->recipient_id,
            'message' => $this->message,
            'created_at' => now(),
        ]);

        $this->message = '';
        $this->dispatch('chat-message-sent');
    }

    protected function markAsDelivered(): void
    {
        if (!$this->recipient_id) {
            return;
        }

        ChatMessage::where('user_id', $this->recipient_id)
            ->where('recipient_id', Auth::id())
            ->whereNull('delivered_at')
            ->update(['delivered_at' => now()]);
    }

    protected function markAsSeen(): void
    {
        if (!$this->recipient_id) {
            return;
        }

        ChatMessage::where('user_id', $this->recipient_id)
            ->where('recipient_id', Auth::id())
            ->whereNull('seen_at')
            ->update(['delivered_at' => now(), 'seen_at' => now()]);
    }

    public function render()
    {
        $messages = collect();
        $messageCounts = [];

        if ($this->recipient_id) {
            $this->markAsDelivered();
            $this->markAsSeen();

            $messages = ChatMessage::with('user')
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
                          ->where('recipient_id', $this->recipient_id);
                })
                ->orWhere(function ($query) {
                    $query->where('user_id', $this->recipient_id)
                          ->where('recipient_id', Auth::id());
                })
                ->latest()
                ->take(20)
                ->get()
                ->reverse();

            $last = $messages->last();
            $key = $last ? ($last->id ?? $last->created_at->timestamp) : null;
            if (!is_null($this->lastMessageKey) && $key !== $this->lastMessageKey && $last && $last->user_id !== Auth::id()) {
                $this->dispatch('chat-message-received');
            }
            $this->lastMessageKey = $key;
        }

        $dbCountsAssigned = ChatMessage::where('recipient_id', Auth::id())
            ->whereNull('seen_at')
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->pluck('count', 'user_id')
            ->toArray();

        $dbCountsUnassigned = ChatMessage::whereNull('recipient_id')
            ->whereNull('seen_at')
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->pluck('count', 'user_id')
            ->toArray();

        $dbCounts = $dbCountsAssigned;
        foreach ($dbCountsUnassigned as $userId => $count) {
            $dbCounts[$userId] = ($dbCounts[$userId] ?? 0) + $count;
        }

        $messageCounts = $dbCounts;

        $authId = Auth::id();
        $lastMessages = ChatMessage::where(function ($query) use ($authId) {
                $query->where('user_id', $authId)
                      ->orWhere('recipient_id', $authId);
            })
            ->latest()
            ->get()
            ->groupBy(function ($msg) use ($authId) {
                return $msg->user_id === $authId ? $msg->recipient_id : $msg->user_id;
            })
            ->map->first();

        $users = User::where('id', '!=', $authId)
            ->get()
            ->sortByDesc(function ($user) use ($lastMessages) {
                return optional($lastMessages[$user->id] ?? null)->created_at;
            })
            ->values();

        return view('livewire.admin.chat', [
            'users' => $users,
            'messages' => $messages,
            'messageCounts' => $messageCounts,
            'lastMessages' => $lastMessages,
            'retention' => $this->retentionPeriod(),
        ]);
    }

    public function showTyping($event): void
    {
        if (($event['user_id'] ?? null) === $this->recipient_id) {
            $this->typing = now();
        }
    }

    public function getIsTypingProperty(): bool
    {
        return $this->typing && now()->diffInSeconds($this->typing) < 5;
    }

    protected function retentionPeriod(): string
    {
        $hours = Setting::get('chat_retention_hours', config('chat.retention_hours'));
        return $hours % 24 === 0
            ? ($hours / 24) . ' days'
            : $hours . ' hours';
    }
}
