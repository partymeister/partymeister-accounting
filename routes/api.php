<?php

Route::group([
    'middleware' => [ 'auth:api', 'bindings', 'permission' ],
    'namespace'  => 'Partymeister\Accounting\Http\Controllers\Api',
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::resource('account_types', 'AccountTypesController');
    Route::resource('accounts', 'AccountsController');
    Route::resource('bookings', 'BookingsController');
    Route::resource('item_types', 'ItemTypesController');
    Route::resource('items', 'ItemsController');
});
