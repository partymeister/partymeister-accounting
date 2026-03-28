<?php
// Legacy backend web routes commented out — backend uses V2 API
/*
use Partymeister\Accounting\Http\Controllers\Backend\AccountsController;
use Partymeister\Accounting\Http\Controllers\Backend\AccountTypesController;
use Partymeister\Accounting\Http\Controllers\Backend\BookingsController;
use Partymeister\Accounting\Http\Controllers\Backend\ItemsController;
use Partymeister\Accounting\Http\Controllers\Backend\ItemTypesController;
use Partymeister\Accounting\Http\Controllers\Backend\PosInterfacesController;
use Partymeister\Accounting\Http\Controllers\Backend\SalesController;

Route::group([
    'as' => 'backend.',
    'prefix' => 'backend',
    'middleware' => [
        'web',
        'web_auth',
        'navigation',
    ],
], function () {
    Route::resource('account_types', AccountTypesController::class);
    Route::resource('accounts', AccountsController::class);
    Route::resource('bookings', BookingsController::class);
    Route::resource('item_types', ItemTypesController::class);
    Route::resource('items', ItemsController::class);
    Route::resource('sales', SalesController::class);
    Route::get('pos/{account}', [PosInterfacesController::class, 'show'])
        ->name('pos.show');
    Route::get('pos/{account}/configured', [Partymeister\Accounting\Http\Controllers\Api\PosInterfacesController::class, 'configured'])
        ->name('pos.configured');
    Route::get('pos/{account}/editor', [Partymeister\Accounting\Http\Controllers\Api\PosInterfacesController::class, 'editor'])
        ->name('pos.editor');
    Route::post('pos/{account}', [PosInterfacesController::class, 'create'])
        ->name('pos.create');
    Route::get('pos/edit/{account}', [PosInterfacesController::class, 'edit'])
        ->name('pos.edit');
    Route::patch('pos/edit/{account}', [PosInterfacesController::class, 'update'])
        ->name('pos.update');
});
*/
