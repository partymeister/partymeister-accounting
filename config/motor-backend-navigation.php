<?php

return [
    'items' => [
        110 => [
            'slug'        => 'partymeister-accounting',
            'name'        => 'partymeister-accounting::backend/global.accounting',
            'icon'        => 'fa fa-home',
            'route'       => null,
            'roles'       => [ 'SuperAdmin' ],
            'permissions' => [ 'partymeister.read' ],
            'items'       => [
                10 => [ // <-- !!! replace 847 with your own sort position !!!
                    'slug' => 'accounts',
                    'name'  => 'partymeister-accounting::backend/accounts.accounts',
                    'icon'  => 'fa fa-plus',
                    'route' => 'backend.accounts.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'accounts.read' ],
                ],
                20 => [ // <-- !!! replace 149 with your own sort position !!!
                    'slug' => 'account_types',
                    'name'  => 'partymeister-accounting::backend/account_types.account_types',
                    'icon'  => 'fa fa-plus',
                    'route' => 'backend.account_types.index',
                    'roles'       => [ 'SuperAdmin' ],
                    'permissions' => [ 'account_types.read' ],
                ],
            ]
        ],
    ]
];