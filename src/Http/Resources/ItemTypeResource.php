<?php

namespace Partymeister\Accounting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
class ItemTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $comesFromItemEndpoint = ($request->route()
                                          ->uri() === 'api/items') ? true : false;

        return [
            'id'            => (int) $this->id,
            'name'          => $this->name,
            'is_visible'    => (boolean) $this->is_visible,
            'sort_position' => (int) $this->sort_position,
            'items'         => $this->when(! $comesFromItemEndpoint, ItemResource::collection($this->items)),
        ];
    }
}
