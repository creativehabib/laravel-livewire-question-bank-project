<?php

namespace App\Livewire\Admin;

use App\Events\ChatAssigned;
use App\Models\Chat;
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
        $chat = Chat::firstOrCreate(['user_id' => $value]);
        if ($chat->assigned_admin_id !== Auth::id()) {
            $chat->assigned_admin_id = Auth::id();
            $chat->save();

            event(new ChatAssigned($chat));
        }
    }

    public function send()
    {
        $this->validate();

        $messages = Cache::get('chat.pending', []);
        $messages[] = [
            'user_id' => Auth::id(),
            'recipient_id' => $this->recipient_id,
            'message' => $this->message,
            'created_at' => now(),
        ];

        Cache::put('chat.pending', $messages, now()->addMinutes(1));

        $this->message = '';
        $this->dispatch('chat-message-sent');
    }

    public function render()
    {
        $messages = collect();
        $messageCounts = [];

        if ($this->recipient_id) {
            ChatMessage::where('user_id', $this->recipient_id)
                ->where('recipient_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $pending = Cache::get('chat.pending', []);
            foreach ($pending as &$msg) {
                if ($msg['user_id'] == $this->recipient_id && $msg['recipient_id'] == Auth::id()) {
                    $msg['read_at'] = now();
                }
            }
            unset($msg);
            Cache::put('chat.pending', $pending, now()->addMinutes(1));

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
                    return (object) $msg;
                });

            $messages = $messages->toBase()->merge($cached)->sortBy('created_at')->values();
        }

        $dbCounts = ChatMessage::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->pluck('count', 'user_id')
            ->toArray();

        $cachedCounts = [];
        foreach (Cache::get('chat.pending', []) as $msg) {
            if ($msg['recipient_id'] == Auth::id() && empty($msg['read_at'])) {
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
}
