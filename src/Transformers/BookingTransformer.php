<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\Booking;

class BookingTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];


    /**
     * Transform record to array
     *
     * @param Booking $record
     *
     * @return array
     */
    public function transform(Booking $record)
    {
        return [
            'id'        => (int) $record->id
        ];
    }
}
