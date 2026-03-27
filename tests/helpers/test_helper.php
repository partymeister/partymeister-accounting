<?php

use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\AccountType;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Partymeister\Accounting\Models\Sale;

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_account_type($count = 1)
{
    return factory(AccountType::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_account($count = 1)
{
    return factory(Account::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_booking($count = 1)
{
    return factory(Booking::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_item_type($count = 1)
{
    return factory(ItemType::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_item($count = 1)
{
    return factory(Item::class, $count)->create();
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_items_for_earnings_account($earningsAccountId, $count = 1)
{
    return factory(
        Item::class,
        $count
    )->create(['pos_earnings_account_id' => $earningsAccountId]);
}

/**
 * @param  int  $count
 * @return mixed
 */
function create_test_sale($count = 1)
{
    return factory(Sale::class, $count)->create();
}
