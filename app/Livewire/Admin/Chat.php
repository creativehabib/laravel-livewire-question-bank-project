<?php

namespace App\Livewire\Admin;

use App\Events\ChatAssigned;
use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Chat as ChatModel;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
            "echo-private:chat.{$userId},MessageSent" => '$refresh',
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

            $pending = Cache::get('chat.pending', []);
            foreach ($pending as &$msg) {
                if ($msg['user_id'] == $value && empty($msg['recipient_id'])) {
                    $msg['recipient_id'] = Auth::id();
                }
            }
            unset($msg);
            Cache::put('chat.pending', $pending, now()->addMinutes(1));

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

        $messages = Cache::get('chat.pending', []);
        $payload = [
            'user_id' => Auth::id(),
            'recipient_id' => $this->recipient_id,
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
        if (!$this->recipient_id) {
            return;
        }

        ChatMessage::where('user_id', $this->recipient_id)
            ->where('recipient_id', Auth::id())
            ->whereNull('delivered_at')
            ->update(['delivered_at' => now()]);

        $pending = Cache::get('chat.pending', []);
        foreach ($pending as &$msg) {
            if ($msg['user_id'] == $this->recipient_id && $msg['recipient_id'] == Auth::id() && empty($msg['delivered_at'])) {
                $msg['delivered_at'] = now();
            }
        }
        unset($msg);
        Cache::put('chat.pending', $pending, now()->addMinutes(1));
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

        $pending = Cache::get('chat.pending', []);
        foreach ($pending as &$msg) {
            if ($msg['user_id'] == $this->recipient_id && $msg['recipient_id'] == Auth::id()) {
                $msg['delivered_at'] = $msg['delivered_at'] ?? now();
                $msg['seen_at'] = now();
            }
        }
        unset($msg);
        Cache::put('chat.pending', $pending, now()->addMinutes(1));
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

            $cached = collect(Cache::get('chat.pending', []))
                ->filter(function ($msg) {
                    return ($msg['user_id'] === Auth::id() && $msg['recipient_id'] == $this->recipient_id)
                        || ($msg['user_id'] == $this->recipient_id && $msg['recipient_id'] == Auth::id());
                })
                ->map(function ($msg) {
                    $msg['user'] = User::find($msg['user_id']);
                    $msg['created_at'] = \Illuminate\Support\Carbon::parse($msg['created_at']);
                    $msg['delivered_at'] = isset($msg['delivered_at']) ? \Illuminate\Support\Carbon::parse($msg['delivered_at']) : null;
                    $msg['seen_at'] = isset($msg['seen_at']) ? \Illuminate\Support\Carbon::parse($msg['seen_at']) : null;
                    return (object) $msg;
                });

            $messages = $messages->toBase()->merge($cached)->sortBy('created_at')->values();

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

        $cachedCounts = [];
        foreach (Cache::get('chat.pending', []) as $msg) {
            if ((empty($msg['recipient_id']) || $msg['recipient_id'] == Auth::id()) && empty($msg['seen_at'])) {
                $cachedCounts[$msg['user_id']] = ($cachedCounts[$msg['user_id']] ?? 0) + 1;
            }
        }

        $messageCounts = $dbCounts;
        foreach ($cachedCounts as $userId => $count) {
            $messageCounts[$userId] = ($messageCounts[$userId] ?? 0) + $count;
        }

        return view('livewire.admin.chat', [
            'users' => User::where('id', '!=', Auth::id())->get(),
            'messages' => $messages,
            'messageCounts' => $messageCounts,
            'retentionDays' => Setting::get('chat_retention_days', config('chat.retention_days')),
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
