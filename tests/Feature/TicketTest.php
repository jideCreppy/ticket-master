<?php

use App\Http\Permissions\V1\Abilities;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\post;

uses()->group('tickets');

beforeEach(function () {
    $this->author = User::factory()->create();
    $this->admin = User::factory()->create(['is_admin' => true]);

    $this->payload = [
        'data' => [
            'attributes' => [
                'title' => 'First Ticket',
                'description' => 'First Ticket being created',
                'status' => 'A',
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'id' => $this->author->id,
                    ],
                ],
            ],
        ],
    ];
});

it('can create a self-assigned ticket as a non-admin user', function () {
    $url = route('tickets.store');

    Sanctum::actingAs(
        $this->author,
        Abilities::getAbilities($this->author)
    );

    $response = post($url, $this->payload);

    $response->assertCreated();
});

it('cannot create a ticket for another user as a non-admin user', function () {
    $url = route('tickets.store');

    Sanctum::actingAs(
        $this->author,
        Abilities::getAbilities($this->author)
    );

    $anotherUser = User::factory()->create();

    $this->payload['data']['relationships']['author']['data']['id'] = $anotherUser->id;

    $response = $this->actingAs($this->author)->postJson($url, $this->payload);
    $response->assertJsonFragment(['status' => 422]);
});

it('can create a ticket for another user as an admin user', function () {
    $url = route('tickets.store');

    Sanctum::actingAs(
        $this->admin,
        Abilities::getAbilities($this->admin)
    );

    $anotherUser = User::factory()->create();

    $this->payload['data']['relationships']['author']['data']['id'] = $anotherUser->id;

    $response = $this->actingAs($this->admin)->postJson($url, $this->payload);

    $response->assertCreated();
});
