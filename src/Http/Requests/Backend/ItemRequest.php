<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class ItemRequest
 * @package Partymeister\Accounting\Http\Requests\Backend
 */
class ItemRequest extends Request
{

    /**
     * @OA\Schema(
     *   schema="ItemRequest",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Example data"
     *   ),
     *   required={"name"},
     * )
     */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_type_id' => 'required',
            'name'         => 'required',
        ];
    }
}
