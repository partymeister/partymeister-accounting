<?php

namespace Partymeister\Accounting\Grid\Renderers;

use Motor\Backend\Grid\Renderers\CurrencyRenderer;

/**
 * Class BalanceRowRenderer
 */
class BalanceRowRenderer
{
    /**
     * @var array
     */
    protected $options = [];

    protected $paginator;

    /**
     * @var string
     */
    protected $defaultCurrency = 'EUR';

    /**
     * BalanceRowRenderer constructor.
     */
    public function __construct($options, $paginator)
    {
        $this->options = $options;
        $this->paginator = $paginator;
    }

    /**
     * @return string
     */
    public function render()
    {
        $balance = 0;
        $currency = $this->defaultCurrency;
        foreach ($this->paginator->getCollection() as $record) {
            $currency = $record->currency_iso_4217;
            $balance += $record->cash_balance;
        }

        $renderer = new CurrencyRenderer($balance, ['currency' => $currency]);

        return $renderer->render();
    }
}
