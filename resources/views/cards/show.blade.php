@extends('layouts.app')

@section('title', $card->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('cards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to cards</a>
    </div>

    <h1 class="text-2xl font-bold mb-6">{{ $card->name }}</h1>

    <dl class="grid grid-cols-[auto_1fr] gap-x-6 gap-y-2 text-sm max-w-lg">
        <dt class="font-medium text-gray-500">Card ID</dt>
        <dd class="font-mono">{{ $card->card_id }}</dd>

        @if($card->pack_id)
            <dt class="font-medium text-gray-500">Pack ID</dt>
            <dd>{{ $card->pack_id }}</dd>
        @endif

        <dt class="font-medium text-gray-500">Category</dt>
        <dd>{{ $card->category->value }}</dd>

        <dt class="font-medium text-gray-500">Rarity</dt>
        <dd>{{ $card->rarity->value }}</dd>

        <dt class="font-medium text-gray-500">Colors</dt>
        <dd>{{ $card->colors->pluck('value')->join(', ') }}</dd>

        <dt class="font-medium text-gray-500">Cost</dt>
        <dd>{{ $card->cost ?? '—' }}</dd>

        <dt class="font-medium text-gray-500">Power</dt>
        <dd>{{ $card->power ?? '—' }}</dd>

        <dt class="font-medium text-gray-500">Counter</dt>
        <dd>{{ $card->counter ?? '—' }}</dd>

        @if($card->types)
            <dt class="font-medium text-gray-500">Types</dt>
            <dd>{{ implode(', ', $card->types) }}</dd>
        @endif

        @if($card->attributes)
            <dt class="font-medium text-gray-500">Attributes</dt>
            <dd>{{ implode(', ', $card->attributes) }}</dd>
        @endif

        @if($card->effect)
            <dt class="font-medium text-gray-500">Effect</dt>
            <dd>{{ $card->effect }}</dd>
        @endif

        @if($card->trigger)
            <dt class="font-medium text-gray-500">Trigger</dt>
            <dd>{{ $card->trigger }}</dd>
        @endif
    </dl>
@endsection
