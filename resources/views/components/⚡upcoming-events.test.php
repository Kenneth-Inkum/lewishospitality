<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('upcoming-events')
        ->assertStatus(200);
});
