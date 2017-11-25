<?php

return [
    'items' => [
        110 => [
            'slug'        => 'partymeister-accounting',
            'name'        => 'partymeister-accounting::backend/global.accounting',
            'icon'        => 'fa fa-money',
            'route'       => null,
            'roles'       => [ 'SuperAdmin' ],
            'permissions' => [ 'partymeister.read' ],
            'items'       => [
                10 => [ // <-- !!! replace 847 with your own sort position !!!
                    'slug'        => 'accounts',
                    'name'        => 'partymeister-accounting::backend/accounts.accounts',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'backend.accounts.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'accounts.read' ],
                ],
                20 => [ // <-- !!! replace 149 with your own sort position !!!
                    'slug'        => 'account_types',
                    'name'        => 'partymeister-accounting::backend/account_types.account_types',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'backend.account_types.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'account_types.read' ],
                ],
                30 => [ // <-- !!! replace 512 with your own sort position !!!
                    'slug'        => 'bookings',
                    'name'        => 'partymeister-accounting::backend/bookings.bookings',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'backend.bookings.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'bookings.read' ],
                ],
                40 => [ // <-- !!! replace 845 with your own sort position !!!
                    'slug'        => 'items',
                    'name'        => 'partymeister-accounting::backend/items.items',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'backend.items.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'items.read' ],
                ],
                50 => [ // <-- !!! replace 435 with your own sort position !!!
                    'slug'        => 'item_types',
                    'name'        => 'partymeister-accounting::backend/item_types.item_types',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'backend.item_types.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'item_types.read' ],
                ],
                60 => [ // <-- !!! replace 612 with your own sort position !!!
                    'slug'        => 'sales',
                    'name'        => 'partymeister-accounting::backend/sales.sales',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'backend.sales.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'sales.read' ],
                ],
            ]
        ],
    ]
];