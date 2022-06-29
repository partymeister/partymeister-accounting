<?php

namespace Partymeister\Accounting\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Accounting\Models\Sale;

/**
 * Class SaleService
 */
class SaleService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Sale::class;
}
