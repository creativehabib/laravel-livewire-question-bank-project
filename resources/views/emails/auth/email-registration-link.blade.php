<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Confirm your registration for :app', ['app' => config('app.name')]) }}</title>
</head>
<body>
    <p>{{ __('Hello :name,', ['name' => $name]) }}</p>
    <p>{{ __('You requested to register as a :role on :app.', ['role' => $role === \App\Enums\Role::TEACHER ? __('teacher') : __('student'), 'app' => config('app.name')]) }}</p>
    <p>{{ __('Click the button below to confirm your email address and complete your registration. The link will expire in 60 minutes.') }}</p>
    <p><a href="{{ $url }}" style="display:inline-block;padding:10px 16px;background-color:#4f46e5;color:#ffffff;text-decoration:none;border-radius:6px;">{{ __('Confirm registration') }}</a></p>
    <p>{{ __('If you did not start this registration, please ignore this email.') }}</p>
    <p>{{ __('Thanks,') }}<br>{{ config('app.name') }}</p>
</body>
</html>
