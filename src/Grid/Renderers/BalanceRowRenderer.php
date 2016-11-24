<?php

namespace Partymeister\Accounting\Grid\Renderers;

use Motor\Backend\Grid\Renderers\CurrencyRenderer;

class BalanceRowRenderer
{

    protected $options = [];

    protected $paginator;


    public function __construct($options, $paginator)
    {
        $this->options   = $options;
        $this->paginator = $paginator;
    }


    public function render()
    {
        $balance = 0;
        foreach ($this->paginator->getCollection() as $record) {
            $balance += $record->balance;
        }

        $renderer = new CurrencyRenderer($balance);

        return $renderer->render();
    }
}