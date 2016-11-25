<?php

namespace Partymeister\Accounting\Grid\Renderers;

use Motor\Backend\Grid\Renderers\CurrencyRenderer;

class BalanceRowRenderer
{

    protected $options = [];

    protected $paginator;

    protected $defaultCurrency = 'EUR';


    public function __construct($options, $paginator)
    {
        $this->options   = $options;
        $this->paginator = $paginator;
    }


    public function render()
    {
        $balance = 0;
        $currency = $this->defaultCurrency;
        foreach ($this->paginator->getCollection() as $record) {
            $currency = $record->currency_iso_4217;
            $balance += $record->balance;
        }

        $renderer = new CurrencyRenderer($balance, [ 'currency' => $currency ]);

        return $renderer->render();
    }
}