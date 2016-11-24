<?php

Route::group([
    'middleware' => [ 'auth:api', 'bindings', 'permission' ],
    'namespace'  => 'Partymeister\Accounting\Http\Controllers\Api',
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::resource('account_types', 'AccountTypesController');
    Route::resource('accounts', 'AccountsController');
});
