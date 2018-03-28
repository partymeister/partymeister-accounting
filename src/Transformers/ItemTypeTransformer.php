<?php

namespace Partymeister\Accounting\Transformers;

use League\Fractal;
use Partymeister\Accounting\Models\ItemType;

class ItemTypeTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [ 'items' ];

    /**
     * Transform record to array
     *
     * @param ItemType $record
     *
     * @return array
     */
    public function transform(ItemType $record)
    {
        return [
            'id'            => (int) $record->id,
            'name'          => $record->name,
            'sort_position' => $record->sort_position,
            'is_visible'    => (bool) $record->is_visible
        ];
    }


    public function includeItems(ItemType $record)
    {
        if ($record->items->count() > 0) {
            return $this->collection($record->items()->orderBy('sort_position', 'ASC')->get(), new ItemTransformer());
        }
    }
}
