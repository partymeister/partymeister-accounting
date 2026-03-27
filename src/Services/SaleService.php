<?php

namespace Partymeister\Accounting\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Accounting\Models\Sale;

/**
 * Class SaleService
 */
class SaleService extends BaseService
{
    protected string $model = Sale::class;

    protected array $loadColumns = ['item', 'earnings_booking', 'cost_booking'];
}
