<?php

return [
    'groups'     => [
        'partymeister-accounting' => [
            'name' => 'Partymeister accounting',
        ],
    ],
    'components' => [
        'item_list' => [
            'name'            => 'ItemList',
            'description'     => 'Show ItemList component',
            'view'            => 'partymeister-accounting::frontend.components.item-list',
            'component_class' => 'Partymeister\Accounting\Components\ComponentItemLists',
            'compatibility'   => [

            ],
            'permissions'     => [

            ],
            'group'           => 'partymeister-accounting'
        ],
    ],
];
