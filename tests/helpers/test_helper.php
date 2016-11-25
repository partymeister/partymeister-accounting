<?php

function create_test_account_type($count = 1)
{
    return factory(Partymeister\Accounting\Models\AccountType::class, $count)->create();
}

function create_test_account($count = 1)
{
    return factory(Partymeister\Accounting\Models\Account::class, $count)->create();
}

function create_test_booking($count = 1)
{
    return factory(Partymeister\Accounting\Models\Booking::class, $count)->create();
}

function create_test_item_type($count = 1)
{
    return factory(Partymeister\Accounting\Models\ItemType::class, $count)->create();
}
