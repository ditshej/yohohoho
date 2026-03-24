<?php

use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

it('shows validation error for missing file', function () {
    $this->post('/cards-import', [])
        ->assertSessionHasErrors('file');
});

it('shows error for malformed json file', function () {
    $file = UploadedFile::fake()->createWithContent('cards.json', '{ not valid json }}}');

    $this->post('/cards-import', ['file' => $file])
        ->assertSessionHasErrors('file');
});

it('filters imported cards by color', function () {
    $file = UploadedFile::fake()->createWithContent(
        'cards.json',
        json_encode([
            ['id' => 'OP01-001', 'name' => 'Green Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
            ['id' => 'OP01-002', 'name' => 'Red Card', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Red'], 'cost' => 1],
        ]),
    );

    $this->post('/cards-import', ['file' => $file, 'colors' => ['Green']])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(Card::count())->toBe(1)
        ->and(Card::first()->card_id)->toBe('OP01-001');
});

it('displays the import form', function () {
    $this->get('/cards-import/create')
        ->assertSuccessful()
        ->assertSee('Import Cards');
});

it('imports cards from uploaded json file', function () {
    $file = UploadedFile::fake()->createWithContent(
        'cards.json',
        json_encode([
            ['id' => 'OP01-001', 'name' => 'Zoro', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 3],
            ['id' => 'OP01-002', 'name' => 'Nami', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1],
        ]),
    );

    $this->post('/cards-import', ['file' => $file])
        ->assertRedirect(route('cards.index'))
        ->assertSessionHas('success');

    expect(Card::count())->toBe(2);
});
