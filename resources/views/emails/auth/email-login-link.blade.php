<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Login to :app', ['app' => config('app.name')]) }}</title>
</head>
<body>
    <p>{{ __('Hello :name,', ['name' => $user->name]) }}</p>
    <p>{{ __('Use the button below to securely log in to :app. The link will expire in 30 minutes.', ['app' => config('app.name')]) }}</p>
    <p><a href="{{ $url }}" style="display:inline-block;padding:10px 16px;background-color:#4f46e5;color:#ffffff;text-decoration:none;border-radius:6px;">{{ __('Log in now') }}</a></p>
    <p>{{ __('If you did not request this login link, you can safely ignore this email.') }}</p>
    <p>{{ __('Thanks,') }}<br>{{ config('app.name') }}</p>
</body>
</html>
