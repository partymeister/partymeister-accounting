<?php

use Partymeister\Accounting\Http\Controllers\Api\AccountTypesController;
use Partymeister\Accounting\Http\Controllers\Api\AccountsController;
use Partymeister\Accounting\Http\Controllers\Api\BookingsController;
use Partymeister\Accounting\Http\Controllers\Api\ItemTypesController;
use Partymeister\Accounting\Http\Controllers\Api\ItemsController;
use Partymeister\Accounting\Http\Controllers\Api\SalesController;
use Partymeister\Accounting\Http\Controllers\Api\PosInterfacesController;

Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::apiResource('account_types', AccountTypesController::class);
    Route::apiResource('accounts', AccountsController::class);
    Route::apiResource('bookings', BookingsController::class);
    Route::apiResource('item_types', ItemTypesController::class);
    Route::apiResource('items', ItemsController::class);
    Route::apiResource('sales', SalesController::class);
    Route::get('pos/{account}', [PosInterfacesController::class, 'show'])
         ->name('pos.show');
    Route::post('pos/{account}', [PosInterfacesController::class, 'create'])
         ->name('pos.create');
});
