<?php

use App\Enums\CardCategory;
use App\Enums\CardColor;
use App\Enums\CardRarity;
use App\Models\Card;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a card with all attributes', function () {
    $card = Card::factory()->create([
        'card_id' => 'OP15-022',
        'name' => 'Brook',
        'category' => CardCategory::Leader,
        'colors' => [CardColor::Green, CardColor::Black],
        'cost' => null,
        'power' => 5000,
        'rarity' => CardRarity::Leader,
    ]);

    expect($card)
        ->card_id->toBe('OP15-022')
        ->name->toBe('Brook')
        ->category->toBe(CardCategory::Leader)
        ->power->toBe(5000)
        ->rarity->toBe(CardRarity::Leader);

    expect($card->colors)->toContain(CardColor::Green)
        ->and($card->colors)->toContain(CardColor::Black);
});

it('can create a card with only required attributes', function () {
    $card = Card::factory()->create([
        'card_id' => 'OP15-001',
        'name' => 'Test Card',
        'category' => CardCategory::Character,
        'colors' => [CardColor::Green],
        'cost' => null,
        'power' => null,
        'counter' => null,
        'effect' => null,
        'trigger' => null,
    ]);

    expect($card)
        ->card_id->toBe('OP15-001')
        ->cost->toBeNull()
        ->power->toBeNull()
        ->effect->toBeNull();
});

it('enforces unique card_id', function () {
    Card::factory()->create(['card_id' => 'OP15-022']);

    Card::factory()->create(['card_id' => 'OP15-022']);
})->throws(UniqueConstraintViolationException::class);

it('casts colors to array of CardColor enums', function () {
    $card = Card::factory()->create([
        'colors' => [CardColor::Green, CardColor::Black],
    ]);

    $card->refresh();

    expect($card->colors)->toHaveCount(2)
        ->and($card->colors)->toContain(CardColor::Green)
        ->and($card->colors)->toContain(CardColor::Black);
});

it('casts types to array', function () {
    $card = Card::factory()->create([
        'types' => ['Straw Hat Crew', 'Thriller Bark Pirates'],
    ]);

    $card->refresh();

    expect($card->types)->toBeArray()
        ->and($card->types)->toContain('Straw Hat Crew');
});

it('casts category to CardCategory enum', function () {
    $card = Card::factory()->create([
        'category' => CardCategory::Event,
    ]);

    $card->refresh();

    expect($card->category)->toBe(CardCategory::Event);
});
