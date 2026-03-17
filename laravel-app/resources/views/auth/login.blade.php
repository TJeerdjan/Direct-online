<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Direct-Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        do: {
                            dark: '#1c2336',
                            darker: '#0e172c',
                            accent: '#129387',
                            cta: '#f59d0e',
                            light: '#fafaf8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-do-dark min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-do-accent tracking-tight" data-testid="login-brand">Direct-Online</h1>
            <p class="text-white/40 text-sm mt-1">Dashboard</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8" data-testid="login-form">
            <h2 class="text-xl font-semibold text-do-darker mb-6">Inloggen</h2>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mb-4" data-testid="login-error">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-do-mid mb-1.5">E-mailadres</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none transition"
                               placeholder="jouw@email.nl" data-testid="login-email-input">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-do-mid mb-1.5">Wachtwoord</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-do-accent focus:border-do-accent outline-none transition"
                               placeholder="Wachtwoord" data-testid="login-password-input">
                    </div>
                    <button type="submit" class="w-full bg-do-accent hover:bg-do-accent/90 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition"
                            data-testid="login-submit-btn">
                        Inloggen
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-white/20 text-xs mt-8">&copy; {{ date('Y') }} Direct-Online. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
