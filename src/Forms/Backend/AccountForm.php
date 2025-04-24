<?php

namespace Partymeister\Accounting\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Partymeister\Accounting\Models\AccountType;
use Symfony\Component\Intl\Currencies;

/**
 * Class AccountForm
 */
class AccountForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('account_type_id', 'select2', [
            'label' => trans('partymeister-accounting::backend/account_types.account_type'),
            'choices' => AccountType::pluck('name', 'id')
                ->toArray(),
        ])
            ->add('currency_iso_4217', 'select2', [
                'label' => trans('partymeister-accounting::backend/accounts.currency_iso_4217'),
                'choices' => $currencies = Currencies::getNames(),
                'default_value' => 'EUR',
            ])
            ->add('name', 'text', ['label' => trans('motor-backend::backend/global.name'), 'rules' => 'required'])
            ->add('has_pos', 'checkbox', ['label' => trans('partymeister-accounting::backend/accounts.has_pos')])
            ->add('has_coupon_payments', 'checkbox', ['label' => trans('partymeister-accounting::backend/accounts.has_coupon_payments')])
            ->add('has_card_payments', 'checkbox', ['label' => trans('partymeister-accounting::backend/accounts.has_card_payments')])
            ->add('submit', 'submit', [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => trans('partymeister-accounting::backend/accounts.save'),
            ]);
    }
}
