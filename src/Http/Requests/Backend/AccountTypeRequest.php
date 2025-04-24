<?php

namespace Partymeister\Accounting\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class AccountTypeRequest
 */
class AccountTypeRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="AccountTypeRequest",
     *
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Cash account"
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
            'name' => 'required',
        ];
    }
}
