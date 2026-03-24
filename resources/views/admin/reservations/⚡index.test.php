<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::reservations.index')
        ->assertStatus(200);
});
