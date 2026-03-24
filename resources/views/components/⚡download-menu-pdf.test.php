<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('download-menu-pdf')
        ->assertStatus(200);
});
