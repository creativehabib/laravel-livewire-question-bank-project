@props(['msg', 'showAvatar' => false, 'maxWidth' => 'max-w-xs'])
<div class="flex items-end {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
    @if($showAvatar && $msg->user_id !== auth()->id())
        @if ($msg->user->avatar_url)
            <img src="{{ $msg->user->avatar_url }}" class="w-6 h-6 rounded-full mr-2">
        @else
            <span class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700 mr-2">{{ $msg->user->initials }}</span>
        @endif
    @endif
    <div class="{{ $maxWidth }} px-3 py-2 rounded-lg text-sm {{ $msg->user_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100' }}">
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
    @if($showAvatar && $msg->user_id === auth()->id())
        @if (auth()->user()->avatar_url)
            <img src="{{ auth()->user()->avatar_url }}" class="w-6 h-6 rounded-full ml-2">
        @else
            <span class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-700 ml-2">{{ auth()->user()->initials }}</span>
        @endif
    @endif
</div>
