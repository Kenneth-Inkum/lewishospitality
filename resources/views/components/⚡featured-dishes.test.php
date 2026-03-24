<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('featured-dishes')
        ->assertStatus(200);
});
