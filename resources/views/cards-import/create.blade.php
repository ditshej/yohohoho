@extends('layouts.app')

@section('title', 'Import Cards')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Import Cards</h1>

    <div x-data="{ tab: '{{ $errors->has('url') ? 'api' : 'file' }}' }" class="max-w-lg">

        {{-- Tab Navigation --}}
        <div class="flex border-b border-gray-200 mb-6">
            <button
                type="button"
                @click="tab = 'file'"
                :class="tab === 'file' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-medium -mb-px"
            >
                File Upload
            </button>
            <button
                type="button"
                @click="tab = 'api'"
                :class="tab === 'api' ? 'border-b-2 border-gray-900 text-gray-900' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-medium -mb-px"
            >
                API Import
            </button>
        </div>

        {{-- File Upload Tab --}}
        <div x-show="tab === 'file'">
            <form action="{{ route('cards-import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
        </div>

        {{-- API Import Tab --}}
        <div x-show="tab === 'api'">
            <form action="{{ route('cards-import.storeFromApi') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="url" class="block text-sm font-medium mb-1">API URL</label>
                    <input
                        type="url"
                        name="url"
                        id="url"
                        value="{{ old('url', 'https://op-cards.ditshej.ch/api/cards') }}"
                        class="block w-full text-sm border border-gray-300 rounded px-3 py-2"
                    >
                    @error('url')
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
                    Import from API
                </button>
            </form>
        </div>

    </div>
@endsection
