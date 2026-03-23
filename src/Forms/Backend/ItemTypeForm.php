<?php

namespace Partymeister\Accounting\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ItemTypeForm
 */
class ItemTypeForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('name', 'text', ['label' => trans('motor-admin::backend/global.name'), 'rules' => 'required'])
             ->add('sort_position', 'text', ['label' => trans('motor-admin::backend/global.sort_position')])
             ->add('is_visible', 'checkbox', ['label' => trans('motor-admin::backend/global.is_visible')])
             ->add('submit', 'submit', [
                 'attr'  => ['class' => 'btn btn-primary'],
                 'label' => trans('partymeister-accounting::backend/item_types.save'),
             ]);
    }
}
