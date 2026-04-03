<?php

use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('imports cards from api url', function () {
    Http::fake([
        '*' => Http::response([
            ['id' => 'OP01-001', 'name' => 'Zoro', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 3],
            ['id' => 'OP01-002', 'name' => 'Nami', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
        ]),
    ]);

    $this->post('/cards-import/api', ['url' => 'https://op-cards.ditshej.ch/api/cards'])
        ->assertRedirect(route('cards.index'))
        ->assertSessionHas('success');

    expect(Card::count())->toBe(2);
});

it('filters cards by color when importing from api', function () {
    Http::fake([
        '*' => Http::response([
            ['id' => 'OP01-001', 'name' => 'Zoro', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 3],
            ['id' => 'OP01-002', 'name' => 'Luffy', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Red'], 'cost' => 1],
        ]),
    ]);

    $this->post('/cards-import/api', [
        'url' => 'https://op-cards.ditshej.ch/api/cards',
        'colors' => ['Green'],
    ])->assertRedirect(route('cards.index'));

    expect(Card::count())->toBe(1)
        ->and(Card::first()->card_id)->toBe('OP01-001');
});

it('shows error when api is not reachable', function () {
    Http::fake([
        '*' => Http::response(null, 500),
    ]);

    $this->post('/cards-import/api', ['url' => 'https://op-cards.ditshej.ch/api/cards'])
        ->assertSessionHasErrors('url');
});

it('shows error when api returns invalid json structure', function () {
    Http::fake([
        '*' => Http::response(['not' => 'an array of cards']),
    ]);

    $this->post('/cards-import/api', ['url' => 'https://op-cards.ditshej.ch/api/cards'])
        ->assertSessionHasErrors('url');
});

it('shows two tabs on import form', function () {
    $this->get('/cards-import/create')
        ->assertSuccessful()
        ->assertSee('File Upload')
        ->assertSee('API Import');
});
