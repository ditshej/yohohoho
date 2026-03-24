@extends('layouts.app')

@section('title', 'Cards')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Cards</h1>

    <form action="{{ route('cards.index') }}" method="GET" class="mb-6 space-y-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="search" class="block text-sm font-medium mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Card name..." class="border border-gray-300 rounded px-3 py-1.5 text-sm">
            </div>

            <div>
                <label for="category" class="block text-sm font-medium mb-1">Category</label>
                <select name="category" id="category" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->value }}" @selected(request('category') === $category->value)>{{ $category->value }}</option>
                    @endforeach
                </select>
            </div>

            <fieldset>
                <legend class="text-sm font-medium mb-1">Colors</legend>
                <div class="flex flex-wrap gap-3">
                    @foreach($colors as $color)
                        <label class="flex items-center gap-1.5 text-sm">
                            <input type="checkbox" name="color[]" value="{{ $color->value }}" class="rounded border-gray-300" @checked(in_array($color->value, request('color', [])))>
                            {{ $color->value }}
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <button type="submit" class="bg-gray-900 text-white px-4 py-1.5 rounded text-sm hover:bg-gray-700">
                Filter
            </button>

            @if(request()->hasAny(['search', 'category', 'color']))
                <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Reset</a>
            @endif
        </div>
    </form>

    @if($cards->isEmpty())
        <p class="text-gray-500">No cards found.</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left">
                    <th class="py-2 pr-4">ID</th>
                    <th class="py-2 pr-4">Name</th>
                    <th class="py-2 pr-4">Category</th>
                    <th class="py-2 pr-4">Rarity</th>
                    <th class="py-2 pr-4">Colors</th>
                    <th class="py-2">Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cards as $card)
                    <tr class="border-b border-gray-100">
                        <td class="py-2 pr-4 font-mono">{{ $card->card_id }}</td>
                        <td class="py-2 pr-4">{{ $card->name }}</td>
                        <td class="py-2 pr-4">{{ $card->category->value }}</td>
                        <td class="py-2 pr-4">{{ $card->rarity->value }}</td>
                        <td class="py-2 pr-4">{{ $card->colors->pluck('value')->join(', ') }}</td>
                        <td class="py-2">{{ $card->cost ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
