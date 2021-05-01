<?php

namespace Partymeister\Accounting\Http\Resources;

use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="AccountTypeResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Cash account"
 *   ),
 * )
 */
class AccountTypeResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'       => (int) $this->id,
            'name'     => $this->name,
            'accounts' => AccountResource::collection($this->whenLoaded('accounts')),
        ];
    }
}
