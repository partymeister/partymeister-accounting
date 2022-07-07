<?php

namespace Partymeister\Accounting\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Accounting\Models\AccountType;

/**
 * Class AccountTypeService
 */
class AccountTypeService extends BaseService
{
    /**
     * @var string
     */
    protected $model = AccountType::class;
}
