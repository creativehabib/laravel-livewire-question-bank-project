<div class="max-w-lg mx-auto">
    @if (session()->has('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Chat retention period</label>
            <div class="flex space-x-2">
                <input type="number" min="1" wire:model="chat_retention_value" class="w-full border rounded p-2">
                <select wire:model="chat_retention_unit" class="border rounded p-2">
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                </select>
            </div>
            @error('chat_retention_value')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            @error('chat_retention_unit')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Chat message max length</label>
            <input type="number" min="1" wire:model="chat_message_max_length" class="w-full border rounded p-2">
            @error('chat_message_max_length')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Chat daily message limit</label>
            <input type="number" min="1" wire:model="chat_daily_message_limit" class="w-full border rounded p-2">
            @error('chat_daily_message_limit')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="chat_ai_enabled" id="chat_ai_enabled">
            <label for="chat_ai_enabled" class="text-sm font-medium">Enable AI responses when admins are offline</label>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">AI Provider</label>
            <select wire:model="chat_ai_provider" class="w-full border rounded p-2">
                <option value="openai">OpenAI</option>
                <option value="gemini">Gemini</option>
            </select>
            @error('chat_ai_provider')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        @php $apiKeyField = $chat_ai_provider . '_api_key'; @endphp
        <div>
            <label class="block text-sm font-medium mb-1">
                {{ $chat_ai_provider === 'openai' ? 'OpenAI' : 'Gemini' }} API Key
            </label>
            <input type="text" wire:model="{{ $apiKeyField }}" class="w-full border rounded p-2">
            @error($apiKeyField)
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Timezone</label>
            <select wire:model="timezone" class="w-full border rounded p-2">
                @foreach($timezones as $tz)
                    <option value="{{ $tz }}">{{ $tz }}</option>
                @endforeach
            </select>
            @error('timezone')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    </form>
</div>
