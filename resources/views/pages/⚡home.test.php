<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::home')
        ->assertStatus(200);
});
