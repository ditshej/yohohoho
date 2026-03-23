<?php

use App\Enums\CardColor;
use App\Models\Card;
use App\Services\CardImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('handles empty input', function () {
    $service = app(CardImportService::class);

    $summary = $service->import(collect());

    expect($summary['imported'])->toBe(0)
        ->and($summary['skipped'])->toBe(0);
});

it('filters cards by color', function () {
    $service = app(CardImportService::class);

    $data = collect([
        ['id' => 'OP01-001', 'name' => 'Green Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
        ['id' => 'OP01-002', 'name' => 'Red Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Red'], 'cost' => 1],
        ['id' => 'OP01-003', 'name' => 'Black Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Black'], 'cost' => 2],
        ['id' => 'OP01-004', 'name' => 'Green-Black Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green', 'Black'], 'cost' => 3],
    ]);

    $summary = $service->import($data, [CardColor::Green, CardColor::Black]);

    expect($summary['imported'])->toBe(3)
        ->and($summary['skipped'])->toBe(1)
        ->and(Card::where('card_id', 'OP01-002')->exists())->toBeFalse();
});

it('imports all cards when no color filter is provided', function () {
    $service = app(CardImportService::class);

    $data = collect([
        ['id' => 'OP01-001', 'name' => 'Green Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
        ['id' => 'OP01-002', 'name' => 'Red Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Red'], 'cost' => 1],
    ]);

    $summary = $service->import($data);

    expect($summary['imported'])->toBe(2)
        ->and($summary['skipped'])->toBe(0)
        ->and(Card::count())->toBe(2);
});

it('skips cards with unrecognized colors when filtering', function () {
    $service = app(CardImportService::class);

    $data = collect([
        ['id' => 'OP01-001', 'name' => 'Unknown Color', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['White'], 'cost' => 1],
        ['id' => 'OP01-002', 'name' => 'Green Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
    ]);

    $summary = $service->import($data, [CardColor::Green]);

    expect($summary['imported'])->toBe(1)
        ->and($summary['skipped'])->toBe(1);
});

it('upserts existing cards', function () {
    $service = app(CardImportService::class);

    Card::factory()->create([
        'card_id' => 'OP01-001',
        'name' => 'Old Name',
    ]);

    $data = collect([
        ['id' => 'OP01-001', 'name' => 'New Name', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
    ]);

    $summary = $service->import($data);

    expect($summary['imported'])->toBe(1)
        ->and(Card::count())->toBe(1)
        ->and(Card::where('card_id', 'OP01-001')->first()->name)->toBe('New Name');
});

it('imports cards with all attributes from vegapull format', function () {
    $service = app(CardImportService::class);

    $data = collect([
        [
            'id' => 'OP01-001',
            'pack_id' => '569101',
            'name' => 'Roronoa Zoro',
            'rarity' => 'SuperRare',
            'category' => 'Character',
            'colors' => ['Green'],
            'cost' => 3,
            'power' => 5000,
            'counter' => 1000,
            'types' => ['Straw Hat Crew'],
            'attributes' => ['Slash'],
            'effect' => 'Some effect text',
            'trigger' => null,
            'img_url' => 'https://example.com/card.png',
        ],
    ]);

    $summary = $service->import($data);

    expect($summary['imported'])->toBe(1)
        ->and($summary['skipped'])->toBe(0);

    $card = Card::where('card_id', 'OP01-001')->first();

    expect($card->name)->toBe('Roronoa Zoro')
        ->and($card->pack_id)->toBe('569101')
        ->and($card->power)->toBe(5000)
        ->and($card->effect)->toBe('Some effect text');
});
