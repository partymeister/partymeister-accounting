<?php

Route::group([
    'as'         => 'backend.',
    'prefix'     => 'backend',
    'namespace'  => 'Partymeister\Accounting\Http\Controllers\Backend',
    'middleware' => [
        'web',
        'web_auth',
        'navigation'
    ]
], function () {
    Route::resource('account_types', 'AccountTypesController');
    Route::resource('accounts', 'AccountsController');
    Route::resource('bookings', 'BookingsController');
    Route::resource('item_types', 'ItemTypesController');
    Route::resource('items', 'ItemsController');
});
