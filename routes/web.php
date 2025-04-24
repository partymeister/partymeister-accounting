<?php

Route::group([
    'as' => 'backend.',
    'prefix' => 'backend',
    'namespace' => 'Partymeister\Accounting\Http\Controllers\Backend',
    'middleware' => [
        'web',
        'web_auth',
        'navigation',
    ],
], function () {
    Route::resource('account_types', 'AccountTypesController');
    Route::resource('accounts', 'AccountsController');
    Route::resource('bookings', 'BookingsController');
    Route::resource('item_types', 'ItemTypesController');
    Route::resource('items', 'ItemsController');
    Route::resource('sales', 'SalesController');
    Route::get('pos/{account}', 'PosInterfacesController@show')
        ->name('pos.show');
    Route::post('pos/{account}', 'PosInterfacesController@create')
        ->name('pos.create');
    Route::get('pos/edit/{account}', 'PosInterfacesController@edit')
        ->name('pos.edit');
    Route::patch('pos/edit/{account}', 'PosInterfacesController@update')
        ->name('pos.update');
});
