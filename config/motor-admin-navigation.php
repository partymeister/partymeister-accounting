<?php

return [
    'items' => [
        110 => [
            'slug'        => 'partymeister-accounting',
            'name'        => 'partymeister-accounting.global.accounting',
            'icon'        => 'euro-sign',
            'route'       => null,
            'roles'       => ['SuperAdmin'],
            'permissions' => ['partymeister.read'],
            'items'       => [
                10 => [ // <-- !!! replace 847 with your own sort position !!!
                    'slug'        => 'accounts',
                    'name'        => 'partymeister-accounting.accounts.accounts',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'admin.partymeister-accounting.accounts',
                    'roles'       => ['SuperAdmin'],
                    'permissions' => ['accounts.read'],
                ],
                20 => [ // <-- !!! replace 149 with your own sort position !!!
                    'slug'        => 'account_types',
                    'name'        => 'partymeister-accounting.account_types.account_types',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'admin.partymeister-accounting.account-types',
                    'roles'       => ['SuperAdmin'],
                    'permissions' => ['account_types.read'],
                ],
                30 => [ // <-- !!! replace 512 with your own sort position !!!
                    'slug'        => 'bookings',
                    'name'        => 'partymeister-accounting.bookings.bookings',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'admin.partymeister-accounting.bookings',
                    'roles'       => ['SuperAdmin'],
                    'permissions' => ['bookings.read'],
                ],
                40 => [ // <-- !!! replace 845 with your own sort position !!!
                    'slug'        => 'items',
                    'name'        => 'partymeister-accounting.items.items',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'admin.partymeister-accounting.items',
                    'roles'       => ['SuperAdmin'],
                    'permissions' => ['items.read'],
                ],
                50 => [ // <-- !!! replace 435 with your own sort position !!!
                    'slug'        => 'item_types',
                    'name'        => 'partymeister-accounting.item_types.item_types',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'admin.partymeister-accounting.item-types',
                    'roles'       => ['SuperAdmin'],
                    'permissions' => ['item_types.read'],
                ],
                60 => [ // <-- !!! replace 612 with your own sort position !!!
                    'slug'        => 'sales',
                    'name'        => 'partymeister-accounting.sales.sales',
                    'icon'        => 'fa fa-angle-right',
                    'route'       => 'admin.partymeister-accounting.sales',
                    'roles'       => ['SuperAdmin'],
                    'permissions' => ['sales.read'],
                ],
            ],
        ],
    ],
];
