<?php

namespace Partymeister\Accounting\Http\Resources;

use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="ItemTypeResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Beverages"
 *   ),
 *   @OA\Property(
 *     property="is_visible",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="sort_position",
 *     type="integer",
 *     example="3"
 *   ),
 * )
 */
class ItemTypeResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => (int) $this->id,
            'name'          => $this->name,
            'is_visible'    => (bool) $this->is_visible,
            'sort_position' => (int) $this->sort_position,
            'items'         => ItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
