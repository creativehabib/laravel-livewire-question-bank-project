<?php

namespace App\Jobs;

use App\Events\ChatMessageSent;
use App\Events\ChatAssigned;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Setting;
use App\Models\User;
use App\Services\AIResponseService;
use App\Enums\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array */
    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        $message = ChatMessage::create($this->payload);
        broadcast(new ChatMessageSent($message));

        $sender = User::find($message->user_id);
        if ($sender && !$sender->isAdmin()) {
            $recipient = $message->recipient_id ? User::find($message->recipient_id) : null;
            $recipientOnline = $recipient ? $recipient->isOnline() : false;

            if (!$recipientOnline) {
                $enabled = Setting::get('chat_ai_enabled', false);
                $apiKey = Setting::get('openai_api_key') ?: config('services.openai.key');

                if ($enabled && $apiKey) {
                    try {
                        $service = app(AIResponseService::class);
                        $reply = $service->generate($message->message, $apiKey);
                    } catch (\Throwable $e) {
                        $reply = null;
                    }

                    if ($reply) {
                        $botUser = $recipient ?? User::where('role', Role::ADMIN)->first();
                        if ($botUser) {
                            $chat = Chat::firstOrCreate(['user_id' => $message->user_id]);
                            if (!$chat->assigned_admin_id) {
                                $chat->assigned_admin_id = $botUser->id;
                                $chat->save();
                                broadcast(new ChatAssigned($chat));
                            }

                            $aiMessage = ChatMessage::create([
                                'user_id' => $botUser->id,
                                'recipient_id' => $message->user_id,
                                'message' => $reply,
                                'created_at' => now(),
                            ]);
                            broadcast(new ChatMessageSent($aiMessage));
                        }
                    }
                }
            }
        }
    }
}
