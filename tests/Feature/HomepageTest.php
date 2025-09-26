<?php

use Illuminate\Testing\TestResponse;

it('loads the welcome page', function () {
    /** @var TestResponse $response */
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Laravel');
});


