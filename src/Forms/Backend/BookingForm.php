<?php

namespace Partymeister\Accounting\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Partymeister\Accounting\Models\Account;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Intl;

/**
 * Class BookingForm
 * @package Partymeister\Accounting\Forms\Backend
 */
class BookingForm extends Form
{

    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('from_account_id', 'select2', [
                'label'       => trans('partymeister-accounting::backend/bookings.from_account'),
                'choices'     => Account::pluck('name', 'id')->toArray(),
                'empty_value' => trans('partymeister-accounting::backend/bookings.book_in')
            ])
             ->add('to_account_id', 'select2', [
                 'label'       => trans('partymeister-accounting::backend/bookings.to_account'),
                 'choices'     => Account::pluck('name', 'id')->toArray(),
                 'empty_value' => trans('partymeister-accounting::backend/bookings.book_out')
             ])
             ->add('description', 'textarea',
                 [ 'label' => trans('partymeister-accounting::backend/bookings.description'), 'rules' => 'required' ])
             ->add('vat_percentage', 'text',
                 [ 'label' => trans('partymeister-accounting::backend/bookings.vat_percentage') ])
             ->add('price_with_vat', 'text',
                 [ 'label' => trans('partymeister-accounting::backend/bookings.price_with_vat') ])
             ->add('price_without_vat', 'text',
                 [ 'label' => trans('partymeister-accounting::backend/bookings.price_without_vat') ])
             ->add('currency_iso_4217', 'select2', [
                 'label'         => trans('partymeister-accounting::backend/accounts.currency_iso_4217'),
                 'choices'       => $currencies = Currencies::getNames(),
                 'default_value' => 'EUR'
             ])
             ->add('is_manual_booking', 'checkbox', [
                 'label'         => trans('partymeister-accounting::backend/bookings.is_manual_booking'),
                 'default_value' => true
             ])
             ->add('submit', 'submit', [
                 'attr'  => [ 'class' => 'btn btn-primary' ],
                 'label' => trans('partymeister-accounting::backend/bookings.save')
             ]);
    }
}
