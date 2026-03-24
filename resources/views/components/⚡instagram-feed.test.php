<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('instagram-feed')
        ->assertStatus(200);
});
