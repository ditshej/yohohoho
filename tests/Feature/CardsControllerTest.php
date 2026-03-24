<?php

use App\Enums\CardCategory;
use App\Enums\CardColor;
use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays all cards', function () {
    $cards = Card::factory()->count(3)->create();

    $response = $this->get(route('cards.index'));

    $response->assertSuccessful();
    foreach ($cards as $card) {
        $response->assertSee($card->name);
        $response->assertSee($card->card_id);
    }
});

it('shows empty state when no cards exist', function () {
    $this->get(route('cards.index'))
        ->assertSuccessful()
        ->assertSee('No cards found');
});

it('filters cards by single color', function () {
    Card::factory()->create(['name' => 'Zoro Roronoa', 'colors' => [CardColor::Green]]);
    Card::factory()->create(['name' => 'Shanks Redhair', 'colors' => [CardColor::Red]]);

    $this->get(route('cards.index', ['color' => ['Green']]))
        ->assertSuccessful()
        ->assertSee('Zoro Roronoa')
        ->assertDontSee('Shanks Redhair');
});

it('filters cards by multiple colors', function () {
    Card::factory()->create(['name' => 'Zoro Roronoa', 'colors' => [CardColor::Green]]);
    Card::factory()->create(['name' => 'Brook Musician', 'colors' => [CardColor::Black]]);
    Card::factory()->create(['name' => 'Shanks Redhair', 'colors' => [CardColor::Red]]);

    $this->get(route('cards.index', ['color' => ['Green', 'Black']]))
        ->assertSuccessful()
        ->assertSee('Zoro Roronoa')
        ->assertSee('Brook Musician')
        ->assertDontSee('Shanks Redhair');
});

it('filters cards by category', function () {
    Card::factory()->create(['name' => 'Zoro Roronoa', 'category' => CardCategory::Character]);
    Card::factory()->create(['name' => 'Diable Jambe', 'category' => CardCategory::Event]);

    $this->get(route('cards.index', ['category' => 'Character']))
        ->assertSuccessful()
        ->assertSee('Zoro Roronoa')
        ->assertDontSee('Diable Jambe');
});

it('searches cards by name', function () {
    $brook = Card::factory()->create(['name' => 'Brook']);
    Card::factory()->create(['name' => 'Zoro']);

    $this->get(route('cards.index', ['search' => 'brook']))
        ->assertSuccessful()
        ->assertSee($brook->name)
        ->assertDontSee('Zoro');
});

it('shows empty state when search has no results', function () {
    Card::factory()->create(['name' => 'Brook']);

    $this->get(route('cards.index', ['search' => 'nonexistent']))
        ->assertSuccessful()
        ->assertSee('No cards found');
});

it('shows card detail page', function () {
    $card = Card::factory()->create([
        'name' => 'Brook',
        'card_id' => 'OP15-022',
        'category' => CardCategory::Leader,
        'effect' => 'Trash 4 cards from the top of your deck.',
    ]);

    $this->get(route('cards.show', $card))
        ->assertSuccessful()
        ->assertSee('Brook')
        ->assertSee('OP15-022')
        ->assertSee('Leader')
        ->assertSee('Trash 4 cards from the top of your deck.');
});

it('returns 404 for non-existent card', function () {
    $this->get(route('cards.show', 999))
        ->assertNotFound();
});

it('combines color and category filters', function () {
    Card::factory()->leader()->create(['name' => 'Brook Conductor', 'colors' => [CardColor::Green]]);
    Card::factory()->create(['name' => 'Zoro Roronoa', 'colors' => [CardColor::Green], 'category' => CardCategory::Character]);
    Card::factory()->leader()->create(['name' => 'Shanks Redhair', 'colors' => [CardColor::Red]]);

    $this->get(route('cards.index', ['color' => ['Green'], 'category' => 'Leader']))
        ->assertSuccessful()
        ->assertSee('Brook Conductor')
        ->assertDontSee('Zoro Roronoa')
        ->assertDontSee('Shanks Redhair');
});
