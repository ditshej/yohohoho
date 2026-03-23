@extends('layouts.app')

@section('title', 'Import Cards')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Import Cards</h1>

    <form action="{{ route('cardsImport.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-lg">
        @csrf

        <div>
            <label for="file" class="block text-sm font-medium mb-1">JSON File (vegapull format)</label>
            <input type="file" name="file" id="file" accept=".json" class="block w-full text-sm border border-gray-300 rounded px-3 py-2">
            @error('file')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <fieldset>
            <legend class="text-sm font-medium mb-2">Filter by Color (optional)</legend>
            <div class="flex flex-wrap gap-3">
                @foreach($colors as $color)
                    <label class="flex items-center gap-1.5 text-sm">
                        <input type="checkbox" name="colors[]" value="{{ $color->value }}" class="rounded border-gray-300" @checked(in_array($color->value, old('colors', [])))>
                        {{ $color->value }}
                    </label>
                @endforeach
            </div>
            @error('colors.*')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </fieldset>

        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
            Import
        </button>
    </form>
@endsection
