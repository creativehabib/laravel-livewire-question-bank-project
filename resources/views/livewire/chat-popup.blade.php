<div x-data="{ open: @entangle('open') }" class="fixed bottom-4 right-4 z-50" wire:poll.5s>
    <button @click="open = !open" class="relative p-3 bg-indigo-600 text-white rounded-full shadow-lg focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">{{ $unreadCount }}</span>
        @endif
    </button>

    <div x-show="open" x-transition class="mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl flex flex-col">
        <div class="flex items-center justify-between p-2 border-b border-gray-200 dark:border-gray-700">
            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $chatTitle }}</span>
            <button @click="open = false" class="text-gray-500">&times;</button>
        </div>
        <div id="chatMessages" class="p-2 overflow-y-auto space-y-2 h-64">
            @forelse($messages as $msg)
                <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="px-3 py-2 rounded-lg max-w-[70%] text-sm {{ $msg->user_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100' }}">
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
                </div>
            @empty
                <div class="text-sm text-gray-500">No messages</div>
            @endforelse
            @if($this->isTyping)
                <div class="text-sm text-gray-500">Typing...</div>
            @endif
        </div>
        @error('message')
            <div class="px-2 text-xs text-red-600">{{ $message }}</div>
        @enderror
        <form wire:submit.prevent="send" class="flex border-t border-gray-200 dark:border-gray-700">
            <input type="text" wire:model="message" class="flex-1 p-2 rounded-bl-lg focus:outline-none dark:bg-gray-800" placeholder="Type a message...">
            <button type="submit" class="px-4 bg-indigo-600 text-white rounded-br-lg">Send</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', () => {
        const scroll = () => {
            const el = document.getElementById('chatMessages');
            if (el) {
                el.scrollTop = el.scrollHeight;
            }
        };

        scroll();
        Livewire.on('chat-message-sent', () => {
            scroll();
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
