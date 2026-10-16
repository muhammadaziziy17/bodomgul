<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Kirish — Bodomgul Admin</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="64x64">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin admin--login">
    <main class="a-login">
        <div class="a-login__card">
            <div class="a-login__brand">
                <img src="{{ asset('images/brand/logo-emblem.png') }}" alt="" width="56" height="56">
                <h1>Bodomgul Admin</h1>
                <p>Sayt kontentini boshqarish uchun tizimga kiring.</p>
            </div>

            @if ($errors->any())
                <div class="a-flash a-flash--error">
                    <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="a-form">
                @csrf

                <x-admin.input
                    name="email"
                    label="Email"
                    type="email"
                    :value="old('email')"
                    required
                    autocomplete="email"
                    autofocus
                />

                <x-admin.input
                    name="password"
                    label="Parol"
                    type="password"
                    required
                    autocomplete="current-password"
                />

                <label class="a-toggle">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span class="a-toggle__box" aria-hidden="true"></span>
                    <span class="a-toggle__label">Meni eslab qol</span>
                </label>

                <button type="submit" class="a-btn a-btn--block">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Kirish
                </button>
            </form>

            <a class="a-login__back" href="{{ route('home') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Saytga qaytish
            </a>
        </div>
    </main>
</body>
</html>
