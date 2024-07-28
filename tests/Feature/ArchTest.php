<?php

it('Tests application wide architecture', function () {
    expect('App\Http\Filters\V1\Filters')->toExtend('App\Http\Filters\V1\QueryFilter');
});
