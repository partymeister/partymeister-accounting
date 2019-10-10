<?php

return [
    'account_types' => [
        'name'   => 'partymeister-accounting::backend/account_types.account_types',
        'values' => [
            'read',
            'write',
            'delete'
        ]
    ],
    'accounts'      => [
        'name'   => 'partymeister-accounting::backend/accounts.accounts',
        'values' => [
            'read',
            'write',
            'delete'
        ]
    ],
    'bookings'      => [
        'name'   => 'partymeister-accounting::backend/bookings.bookings',
        'values' => [
            'read',
            'write',
            'delete'
        ]
    ],
    'item_types'    => [
        'name'   => 'partymeister-accounting::backend/item_types.item_types',
        'values' => [
            'read',
            'write',
            'delete'
        ]
    ],
    'items'         => [
        'name'   => 'partymeister-accounting::backend/items.items',
        'values' => [
            'read',
            'write',
            'delete'
        ]
    ],
    'sales'         => [
        'name'   => 'partymeister-accounting::backend/sales.sales',
        'values' => [
            'read'
        ]
    ],
];
