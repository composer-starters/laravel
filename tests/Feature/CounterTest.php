<?php

use Livewire\Livewire;

it('starts at zero', function () {
    Livewire::test('counter')->assertSet('count', 0);
});

it('increments the count', function () {
    Livewire::test('counter')
        ->call('increment')
        ->assertSet('count', 1)
        ->call('increment')
        ->assertSet('count', 2);
});

it('decrements the count', function () {
    Livewire::test('counter', ['count' => 3])
        ->call('decrement')
        ->assertSet('count', 2);
});

it('cannot decrement past 0', function () {
    Livewire::test('counter', ['count' => 1])
        ->call('decrement')
        ->assertSet('count', 0)
        ->call('decrement')
        ->assertSet('count', 0);
});

it('resets the count to zero', function () {
    Livewire::test('counter', ['count' => 7])
        ->call('clear')
        ->assertSet('count', 0);
});
