<?php

namespace Partymeister\Accounting\Services;

use Illuminate\Support\Arr;
use Partymeister\Accounting\Models\Booking;
use Motor\Backend\Services\BaseService;

class BookingService extends BaseService
{

    protected $model = Booking::class;


    public function beforeCreate()
    {
        $this->convertNumbers();
    }


    public function beforeUpdate()
    {
        $this->convertNumbers();
    }

    protected function convertNumbers()
    {
        $this->data['price_with_vat']    = str_replace(',', '.', Arr::get($this->data, 'price_with_vat', 0));
        $this->data['price_without_vat'] = str_replace(',', '.', Arr::get($this->data, 'price_without_vat', 0));
        $this->data['vat_percentage']    = str_replace(',', '.', Arr::get($this->data, 'vat_percentage', 0));
    }

}
