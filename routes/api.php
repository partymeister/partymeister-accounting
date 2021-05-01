<?php

Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'namespace'  => 'Partymeister\Accounting\Http\Controllers\Api',
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::apiResource('account_types', 'AccountTypesController');
    Route::apiResource('accounts', 'AccountsController');
    Route::apiResource('bookings', 'BookingsController');
    Route::apiResource('item_types', 'ItemTypesController');
    Route::apiResource('items', 'ItemsController');
    Route::apiResource('sales', 'SalesController');
    Route::get('pos/{account}', 'PosInterfacesController@show')
         ->name('pos.show');
    Route::post('pos/{account}', 'PosInterfacesController@create')
         ->name('pos.create');
});
