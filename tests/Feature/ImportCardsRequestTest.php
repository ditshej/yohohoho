<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

it('rejects missing file', function () {
    $this->post('/cards-import', [])
        ->assertSessionHasErrors('file');
});

it('rejects non-json file', function () {
    $file = UploadedFile::fake()->create('cards.csv', 100, 'text/csv');

    $this->post('/cards-import', ['file' => $file])
        ->assertSessionHasErrors('file');
});

it('rejects invalid color values', function () {
    $file = UploadedFile::fake()->createWithContent(
        'cards.json',
        json_encode([['id' => 'OP01-001', 'name' => 'Test', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1]]),
    );

    $this->post('/cards-import', ['file' => $file, 'colors' => ['InvalidColor']])
        ->assertSessionHasErrors('colors.0');
});

it('accepts a valid json file', function () {
    $file = UploadedFile::fake()->createWithContent(
        'cards.json',
        json_encode([['id' => 'OP01-001', 'name' => 'Test', 'rarity' => 'Common', 'category' => 'Character', 'colors' => ['Green'], 'cost' => 1]]),
    );

    $this->post('/cards-import', ['file' => $file])
        ->assertSessionHasNoErrors()
        ->assertRedirect();
});
