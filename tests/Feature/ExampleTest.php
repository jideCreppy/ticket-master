<?php

it('returns a successful response', closure: function () {
    //@phpstan-ignore-next-line
    $response = $this->get('/');

    $response->assertStatus(200);
});
