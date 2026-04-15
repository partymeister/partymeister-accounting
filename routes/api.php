<?php

use Motor\Core\Http\Middleware\V2\V2ErrorHandler;
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
