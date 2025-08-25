<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Chat</h3>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Messages are kept for {{ $retention }} and removed automatically.</p>

    <div class="flex h-72">
        <div class="w-1/3 pr-4 border-r border-gray-200 dark:border-gray-700 overflow-y-auto h-full">
            <ul class="space-y-1">
                @foreach($users as $user)
                    <li
                        wire:click="$set('recipient_id', {{ $user->id }})"
                        class="flex items-center justify-between p-2 rounded-lg cursor-pointer {{ $recipient_id == $user->id ? 'bg-indigo-50 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center space-x-2">
                            @if ($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" class="w-6 h-6 rounded-full">
                            @else
                                <span class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700">{{ $user->initials }}</span>
                            @endif
                            <span class="text-sm text-gray-800 dark:text-gray-100">{{ $user->name }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            @if(isset($messageCounts[$user->id]) && $messageCounts[$user->id] > 0)
                                <span class="text-xs bg-indigo-600 text-white rounded-full px-2">{{ $messageCounts[$user->id] }}</span>
                            @endif
                            <span class="text-xs {{ $user->isOnline() ? 'text-green-500' : 'text-gray-400' }}">●</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="flex-1 pl-4 flex flex-col h-full">
            <div id="chatMessages" class="flex-1 overflow-y-auto mb-4 space-y-2" wire:poll.5s>
                @forelse($messages as $msg)
                    <div class="flex items-end {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        @if($msg->user_id !== auth()->id())
                            @if ($msg->user->avatar_url)
                                <img src="{{ $msg->user->avatar_url }}" class="w-6 h-6 rounded-full mr-2">
                            @else
                                <span class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700 mr-2">{{ $msg->user->initials }}</span>
                            @endif
                        @endif
                        <div class="max-w-xs px-3 py-2 rounded-lg text-sm {{ $msg->user_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100' }}">
                            <div>{{ $msg->message }}</div>
                            <div class="text-[10px] text-right mt-1 opacity-70">
                                {{ $msg->created_at->format('H:i') }}
                                @if($msg->user_id === auth()->id())
                                    @if($msg->seen_at)
                                        <span>- Read</span>
                                    @elseif($msg->delivered_at)
                                        <span>- Delivered</span>
                                    @else
                                        <span>- Sent</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @if($msg->user_id === auth()->id())
                            @if (auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" class="w-6 h-6 rounded-full ml-2">
                            @else
                                <span class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700 ml-2">{{ auth()->user()->initials }}</span>
                            @endif
                        @endif
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No messages</div>
                @endforelse
                @if($this->isTyping)
                    <div class="text-sm text-gray-500">Typing...</div>
                @endif
            </div>

            @if($recipient_id)
                <form wire:submit.prevent="send" class="flex flex-shrink-0">
                    <input type="text" wire:model="message" class="flex-1 rounded-l-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 p-2" placeholder="Type a message...">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-lg">Send</button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scroll = () => {
                const el = document.getElementById('chatMessages');
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            };

            scroll();

            Livewire.on('chat-message-sent', () => {
                scroll();
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Message sent',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });

            Livewire.on('chat-message-received', () => {
                scroll();
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = ctx.createOscillator();
                const gain = ctx.createGain();
                oscillator.type = 'sine';
                oscillator.frequency.value = 1000;
                oscillator.connect(gain);
                gain.connect(ctx.destination);
                oscillator.start();
                oscillator.stop(ctx.currentTime + 0.2);
            });
        });
    </script>
@endpush
