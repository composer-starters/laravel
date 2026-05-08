<?php

it('renders the welcome page', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee(config('app.name'))
        ->assertSee('Laravel '.app()->version())
        ->assertSeeLivewire('counter');
});
