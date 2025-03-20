{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Application Laravel</title>
    {{-- Import de l'output Vite, Mix ou un fichier CSS si vous avez configuré Tailwind --}}
    {{-- Par exemple si vous utilisez Vite, on fait : --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 font-sans antialiased">
    {{-- Barre de navigation simplifiée --}}
    <nav class="bg-white shadow mb-4">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="text-xl font-bold">Mon App</a>
            <div>
                @auth
                    <span class="mr-4">Connecté en tant que {{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="/login" class="text-blue-600 hover:underline mr-4">Se connecter</a>
                    <a href="/register" class="text-blue-600 hover:underline">S'inscrire</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Contenu principal --}}
    <main>
        @yield('content')
    </main>
</body>
</html>
