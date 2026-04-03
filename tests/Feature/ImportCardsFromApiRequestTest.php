<?php

it('rejects missing url', function () {
    $this->post('/cards-import/api', [])
        ->assertSessionHasErrors('url');
});

it('rejects invalid url', function () {
    $this->post('/cards-import/api', ['url' => 'not-a-url'])
        ->assertSessionHasErrors('url');
});

it('rejects invalid color values', function () {
    $this->post('/cards-import/api', [
        'url' => 'https://op-cards.ditshej.ch/api/cards',
        'colors' => ['InvalidColor'],
    ])->assertSessionHasErrors('colors.0');
});
