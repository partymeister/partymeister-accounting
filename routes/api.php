<?php

Route::group([
    'middleware' => [ 'auth:api', 'bindings', 'permission' ],
    'namespace'  => 'Partymeister\Accounting\Http\Controllers\Api',
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::apiResource('account_types', 'AccountTypesController');
    Route::apiResource('accounts', 'AccountsController');
    Route::resource('bookings', 'BookingsController');
    Route::resource('item_types', 'ItemTypesController');
    Route::resource('items', 'ItemsController');
    Route::resource('sales', 'SalesController');
    Route::get('pos/{account}', 'PosInterfacesController@show')->name('pos.show');
    Route::post('pos/{account}', 'PosInterfacesController@create')->name('pos.create');
});
