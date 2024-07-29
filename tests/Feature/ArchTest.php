<?php

uses()->group('arch');

it('Tests application wide architecture', function () {
    expect('App\Http\Filters\V1\Filters')->toExtend('App\Http\Filters\V1\QueryFilter');
});

it('doesnt permit die and dumps within the application', function () {
    expect(['die', 'dumps', 'dd', 'ddd'])->not->toBeUsed();
});
