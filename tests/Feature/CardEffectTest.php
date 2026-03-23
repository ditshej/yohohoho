<?php

use App\Enums\EffectType;
use App\Models\Card;
use App\Models\CardEffect;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a card effect', function () {
    $card = Card::factory()->create();

    $effect = CardEffect::factory()->create([
        'card_id' => $card->id,
        'effect_type' => EffectType::TrashFromDeck,
        'amount' => 3,
        'condition' => null,
    ]);

    expect($effect)
        ->effect_type->toBe(EffectType::TrashFromDeck)
        ->amount->toBe(3)
        ->condition->toBeNull();
});

it('casts effect_type to EffectType enum', function () {
    $card = Card::factory()->create();

    $effect = CardEffect::factory()->create([
        'card_id' => $card->id,
        'effect_type' => EffectType::ReturnFromTrash,
    ]);

    $effect->refresh();

    expect($effect->effect_type)->toBe(EffectType::ReturnFromTrash);
});

it('belongs to a card', function () {
    $card = Card::factory()->create();

    $effect = CardEffect::factory()->create([
        'card_id' => $card->id,
    ]);

    expect($effect->card)->toBeInstanceOf(Card::class)
        ->and($effect->card->id)->toBe($card->id);
});

it('can have a condition', function () {
    $card = Card::factory()->create();

    $effect = CardEffect::factory()->create([
        'card_id' => $card->id,
        'effect_type' => EffectType::TrashFromDeck,
        'amount' => 2,
        'condition' => 'When this character is played',
    ]);

    expect($effect->condition)->toBe('When this character is played');
});

it('has card with multiple effects', function () {
    $card = Card::factory()->create();

    CardEffect::factory()->create([
        'card_id' => $card->id,
        'effect_type' => EffectType::TrashFromDeck,
        'amount' => 3,
    ]);

    CardEffect::factory()->create([
        'card_id' => $card->id,
        'effect_type' => EffectType::Draw,
        'amount' => 1,
    ]);

    expect($card->effects)->toHaveCount(2);
});

it('returns empty collection when card has no effects', function () {
    $card = Card::factory()->create();

    expect($card->effects)->toBeEmpty();
});
