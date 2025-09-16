<div class="max-w-lg mx-auto">
    @if (session()->has('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Chat retention period</label>
            <div class="flex space-x-2">
                <input type="number" min="1" wire:model="chat_retention_value" class="input-field">
                <select wire:model="chat_retention_unit" class="input-field">
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
            <input type="number" min="1" wire:model="chat_message_max_length" class="input-field">
            @error('chat_message_max_length')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Chat daily message limit</label>
            <input type="number" min="1" wire:model="chat_daily_message_limit" class="input-field">
            @error('chat_daily_message_limit')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="chat_tone_enabled" id="chat_tone_enabled">
            <label for="chat_tone_enabled" class="text-sm font-medium">Enable chat message tone</label>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Upload chat message tone</label>
            <input type="file" wire:model="chat_tone" accept="audio/*" class="input-field">
            @if ($chat_tone_url)
                <audio controls class="mt-2" src="{{ $chat_tone_url }}"></audio>
            @endif
            @error('chat_tone')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="chat_ai_enabled" id="chat_ai_enabled">
            <label for="chat_ai_enabled" class="text-sm font-medium">Enable AI responses when admins are offline</label>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">AI Provider</label>
            <select wire:model.live="chat_ai_provider" class="input-field">
                <option value="openai">OpenAI</option>
                <option value="gemini">Gemini</option>
            </select>
            @error('chat_ai_provider')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Admin offline minutes before AI responds</label>
            <input type="number" min="1" wire:model="chat_ai_admin_offline_minutes" class="input-field">
            @error('chat_ai_admin_offline_minutes')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        @if ($chat_ai_provider === 'openai')
            <div wire:key="openai-key-input">
                <label class="block text-sm font-medium mb-1">OpenAI API Key</label>
                <input type="text" wire:model="openai_api_key" class="input-field">
                @error('openai_api_key')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
        @elseif ($chat_ai_provider === 'gemini')
            <div wire:key="gemini-key-input">
                <label class="block text-sm font-medium mb-1">Gemini API Key</label>
                <input type="text" wire:model="gemini_api_key" class="input-field">
                @error('gemini_api_key')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <div>
            <label class="block text-sm font-medium mb-1">Timezone</label>
            <select wire:model="timezone" class="input-field">
                @foreach($timezones as $tz)
                    <option value="{{ $tz }}">{{ $tz }}</option>
                @endforeach
            </select>
            @error('timezone')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="registration_enabled" id="registration_enabled">
            <label for="registration_enabled" class="text-sm font-medium">Allow self registration</label>
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="manual_registration_enabled" id="manual_registration_enabled">
            <label for="manual_registration_enabled" class="text-sm font-medium">Enable manual registration form</label>
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="manual_login_enabled" id="manual_login_enabled">
            <label for="manual_login_enabled" class="text-sm font-medium">Enable manual email &amp; password login</label>
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="google_login_enabled" id="google_login_enabled">
            <label for="google_login_enabled" class="text-sm font-medium">Enable Google Login</label>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Google Client ID</label>
            <input type="text" wire:model="google_client_id" class="input-field">
            @error('google_client_id')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Google Client Secret</label>
            <input type="text" wire:model="google_client_secret" class="input-field">
            @error('google_client_secret')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex items-center space-x-2">
            <input type="checkbox" wire:model="facebook_login_enabled" id="facebook_login_enabled">
            <label for="facebook_login_enabled" class="text-sm font-medium">Enable Facebook Login</label>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Facebook Client ID</label>
            <input type="text" wire:model="facebook_client_id" class="input-field">
            @error('facebook_client_id')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Facebook Client Secret</label>
            <input type="text" wire:model="facebook_client_secret" class="input-field">
            @error('facebook_client_secret')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    </form>
</div>
