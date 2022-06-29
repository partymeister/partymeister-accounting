<?php

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_account_type($count = 1)
{
    return factory(Partymeister\Accounting\Models\AccountType::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_account($count = 1)
{
    return factory(Partymeister\Accounting\Models\Account::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_booking($count = 1)
{
    return factory(Partymeister\Accounting\Models\Booking::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_item_type($count = 1)
{
    return factory(Partymeister\Accounting\Models\ItemType::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_item($count = 1)
{
    return factory(Partymeister\Accounting\Models\Item::class, $count)->create();
}

/**
 * @param    $earningsAccountId
 * @param  int  $count
 * @return mixed
 */
function create_test_items_for_earnings_account($earningsAccountId, $count = 1)
{
    return factory(
        Partymeister\Accounting\Models\Item::class,
        $count
    )->create(['pos_earnings_account_id' => $earningsAccountId]);
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_sale($count = 1)
{
    return factory(Partymeister\Accounting\Models\Sale::class, $count)->create();
}
