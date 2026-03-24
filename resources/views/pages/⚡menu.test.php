<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::menu')
        ->assertStatus(200);
});
