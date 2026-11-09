<?php

test('asset URLs use HTTPS behind a trusted proxy', function () {
    $response = $this
        ->withHeader('X-Forwarded-Proto', 'https')
        ->get('/');

    $response
        ->assertOk()
        ->assertSee('https://localhost:8000/build/assets/', false)
        ->assertSee('https://localhost:8000/images/pickleslot-logo.png', false)
        ->assertDontSee('http://localhost:8000/build/assets/', false)
        ->assertDontSee('http://localhost:8000/images/pickleslot-logo.png', false);
});
