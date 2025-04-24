<?php

namespace Partymeister\Accounting\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class AccountTypeForm
 */
class AccountTypeForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('name', 'text', ['label' => trans('motor-backend::backend/global.name'), 'rules' => 'required'])
            ->add('submit', 'submit', [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => trans('partymeister-accounting::backend/account_types.save'),
            ]);
    }
}
