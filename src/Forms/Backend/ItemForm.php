<?php

namespace Partymeister\Accounting\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Item;
use Partymeister\Accounting\Models\ItemType;
use Symfony\Component\Intl\Intl;

class ItemForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('item_type_id', 'select2', ['label' => trans('partymeister-accounting::backend/item_types.item_type'), 'choices' => ItemType::pluck('name', 'id')->toArray()])
            ->add('name', 'text', ['label' => trans('partymeister-accounting::backend/items.name'), 'rules' => 'required'])
            ->add('description', 'textarea', ['label' => trans('partymeister-accounting::backend/items.description'), 'rules' => 'required'])
            ->add('internal_description', 'textarea', ['label' => trans('partymeister-accounting::backend/items.internal_description')])
            ->add('sort_position', 'text', ['label' => trans('motor-backend::backend/global.sort_position')])
            ->add('can_be_ordered', 'checkbox', ['label' => trans('partymeister-accounting::backend/items.can_be_ordered')])
            ->add('is_visible', 'checkbox', ['label' => trans('motor-backend::backend/global.is_visible')])

            ->add('vat_percentage', 'text', ['label' => trans('partymeister-accounting::backend/bookings.vat_percentage')])
            ->add('price_with_vat', 'text', ['label' => trans('partymeister-accounting::backend/bookings.price_with_vat')])
            ->add('price_without_vat', 'text', ['label' => trans('partymeister-accounting::backend/bookings.price_without_vat')])
            ->add('cost_price_with_vat', 'text', ['label' => trans('partymeister-accounting::backend/items.cost_price_with_vat')])
            ->add('cost_price_without_vat', 'text', ['label' => trans('partymeister-accounting::backend/items.cost_price_without_vat')])
            ->add('currency_iso_4217', 'select2', ['label' => trans('partymeister-accounting::backend/accounts.currency_iso_4217'), 'choices' => $currencies = Intl::getCurrencyBundle()->getCurrencyNames(), 'default_value' => 'EUR'])

            ->add('pos_can_book_negative_quantities', 'checkbox', ['label' => trans('partymeister-accounting::backend/items.pos_can_book_negative_quantities')])
            ->add('is_visible_in_pos', 'checkbox', ['label' => trans('partymeister-accounting::backend/items.is_visible_in_pos')])
            ->add('pos_earnings_account_id', 'select2', ['label' => trans('partymeister-accounting::backend/items.pos_earnings_account'), 'choices' => Account::pluck('name', 'id')->toArray(), 'empty_value' => trans('motor-backend::backend/global.please_choose')])
            ->add('pos_cost_account_id', 'select2', ['label' => trans('partymeister-accounting::backend/items.pos_cost_account'), 'choices' => Account::pluck('name', 'id')->toArray(), 'empty_value' => trans('motor-backend::backend/global.please_choose')])
            ->add('pos_create_booking_for_item_id', 'select2', ['label' => trans('partymeister-accounting::backend/items.pos_create_booking_for_item'), 'choices' => Item::pluck('name', 'id')->toArray(), 'empty_value' => trans('motor-backend::backend/global.please_choose')])
            ->add('pos_sort_position', 'text', ['label' => trans('partymeister-accounting::backend/items.pos_sort_position')])
            ->add('pos_do_break', 'checkbox', ['label' => trans('partymeister-accounting::backend/items.pos_do_break')])

            ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary'], 'label' => trans('partymeister-accounting::backend/items.save')]);
    }
}