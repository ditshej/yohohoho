<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title', 'Brook Simulator')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center gap-6">
            <a href="/" class="font-bold text-lg">Brook Simulator</a>
            <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cards</a>
            <a href="{{ route('cardsImport.create') }}" class="text-sm text-gray-600 hover:text-gray-900">Import Cards</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
