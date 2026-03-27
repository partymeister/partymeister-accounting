<?php

use Motor\Core\Http\Middleware\V2\V2ErrorHandler;
use Partymeister\Accounting\Http\Controllers\Api\AccountsController;
use Partymeister\Accounting\Http\Controllers\Api\AccountTypesController;
use Partymeister\Accounting\Http\Controllers\Api\BookingsController;
use Partymeister\Accounting\Http\Controllers\Api\ItemsController;
use Partymeister\Accounting\Http\Controllers\Api\ItemTypesController;
use Partymeister\Accounting\Http\Controllers\Api\PosInterfacesController;
use Partymeister\Accounting\Http\Controllers\Api\SalesController;
use Partymeister\Accounting\Http\Controllers\Api\V2;

// V2 API routes
Route::prefix('api/v2')
    ->name('v2.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::apiResource('account-types', V2\AccountTypesController::class);
        Route::apiResource('item-types', V2\ItemTypesController::class);
        Route::apiResource('accounts', V2\AccountsController::class);
        Route::apiResource('items', V2\ItemsController::class);
        Route::apiResource('bookings', V2\BookingsController::class);
        Route::apiResource('sales', V2\SalesController::class);

        // Nested account routes
        Route::get('accounts/{account}/items', [V2\Accounts\ItemsController::class, 'index'])
            ->name('accounts.items.index');
        Route::get('accounts/{account}/pos', [V2\Accounts\PosLayoutController::class, 'show'])
            ->name('accounts.pos.show');
        Route::put('accounts/{account}/pos', [V2\Accounts\PosLayoutController::class, 'update'])
            ->name('accounts.pos.update');
    });

// V2 RPC routes
Route::prefix('api/v2/rpc')
    ->name('v2.rpc.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::post('accounts/{account}/book', V2\Rpc\Accounts\BookController::class)
            ->name('accounts.book');
    });

// Legacy API routes (kept as reference)
Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'prefix' => 'api',
    'as' => 'api.',
], function () {
    Route::apiResource('account_types', AccountTypesController::class);
    Route::apiResource('accounts', AccountsController::class);
    Route::apiResource('bookings', BookingsController::class);
    Route::apiResource('item_types', ItemTypesController::class);
    Route::apiResource('items', ItemsController::class);
    Route::apiResource('sales', SalesController::class);
    Route::get('pos/{account}', [PosInterfacesController::class, 'show'])
        ->name('pos.show');
    Route::get('pos/{account}/configured', [PosInterfacesController::class, 'configured'])
        ->name('pos.configured');
    Route::get('pos/{account}/editor', [PosInterfacesController::class, 'editor'])
        ->name('pos.editor');
    Route::post('pos/{account}', [PosInterfacesController::class, 'create'])
        ->name('pos.create');
});
