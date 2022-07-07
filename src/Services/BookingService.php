<?php

namespace Partymeister\Accounting\Services;

use Illuminate\Support\Arr;
use Motor\Admin\Services\BaseService;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Accounting\Models\Account;
use Partymeister\Accounting\Models\Booking;

/**
 * Class BookingService
 */
class BookingService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Booking::class;

    public function filters()
    {
        $this->filter->add(new SelectRenderer('from_account_id'))
                     ->setOptionPrefix(trans('partymeister-accounting::backend/bookings.from_account'))
                     ->setEmptyOption('-- '.trans('partymeister-accounting::backend/bookings.from_account').' --')
                     ->setOptions(Account::pluck('name', 'id'));

        $this->filter->add(new SelectRenderer('to_account_id'))
                     ->setOptionPrefix(trans('partymeister-accounting::backend/bookings.to_account'))
                     ->setEmptyOption('-- '.trans('partymeister-accounting::backend/bookings.to_account').' --')
                     ->setOptions(Account::pluck('name', 'id'));

        $this->filter->add(new SelectRenderer('is_manual_booking'))
                     ->setOptionPrefix(trans('partymeister-accounting::backend/bookings.is_manual_booking'))
                     ->setEmptyOption('-- '.trans('partymeister-accounting::backend/bookings.is_manual_booking').' --')
                     ->setOptions([
                         1 => trans('motor-backend::backend/global.yes'),
                         0 => trans('motor-backend::backend/global.no'),
                     ]);
    }

    public function beforeCreate()
    {
        $this->convertNumbers();
    }

    protected function convertNumbers()
    {
        $this->data['price_with_vat'] = str_replace(',', '.', Arr::get($this->data, 'price_with_vat', 0));
        $this->data['price_without_vat'] = str_replace(',', '.', Arr::get($this->data, 'price_without_vat', 0));
        $this->data['vat_percentage'] = str_replace(',', '.', Arr::get($this->data, 'vat_percentage', 0));
    }

    public function beforeUpdate()
    {
        $this->convertNumbers();
    }
}
