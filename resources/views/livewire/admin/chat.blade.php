<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Chat</h3>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Messages are kept for {{ $retention }} and removed automatically.</p>

    <div class="flex h-72">
        <div class="w-1/3 pr-4 border-r border-gray-200 dark:border-gray-700 overflow-y-auto h-full">
            <ul class="space-y-1">
                @foreach($users as $user)
                    @php $last = $lastMessages[$user->id] ?? null; @endphp
                    <li
                        wire:click="$set('recipient_id', {{ $user->id }})"
                        class="p-2 rounded-lg cursor-pointer {{ $recipient_id == $user->id ? 'bg-indigo-50 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <div class="flex items-center space-x-2">
                            @if ($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full">
                            @else
                                <span class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700">{{ $user->initials }}</span>
                            @endif
                            <div class="flex-1 overflow-hidden">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-800 dark:text-gray-100">{{ $user->name }}</span>
                                    @if($last)
                                        <span class="text-xs text-gray-500">{{ $last->created_at->format('g.ia') }}</span>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 truncate">{{ \Illuminate\Support\Str::limit($last->message ?? '', 30) }}</span>
                                    <div class="flex items-center space-x-1 flex-shrink-0 ml-2">
                                        @if(isset($messageCounts[$user->id]) && $messageCounts[$user->id] > 0)
                                            <span class="text-xs bg-indigo-600 text-white rounded-full px-2">{{ $messageCounts[$user->id] }}</span>
                                        @endif
                                        <span class="text-xs {{ $user->isOnline() ? 'text-green-500' : 'text-gray-400' }}">●</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="flex-1 pl-4 flex flex-col h-full">
            <div id="chatMessages" class="flex-1 overflow-y-auto mb-4 space-y-2" wire:poll.5s>
                @php $lastDate = null; @endphp
                @forelse($messages as $msg)
                    @if ($lastDate !== $msg->created_at->toDateString())
                        <div class="text-center text-xs text-gray-500 my-2">
                            {{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('F j, Y')) }}
                        </div>
                        @php $lastDate = $msg->created_at->toDateString(); @endphp
                    @endif
                    <x-chat-message :msg="$msg" :show-avatar="true" />
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
                @error('message')
                    <div class="text-xs text-red-600 mt-2">{{ $message }}</div>
                @enderror
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
