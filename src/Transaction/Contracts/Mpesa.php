<?php


namespace Snobole\Mpesa\Transaction\Contracts;


interface Mpesa
{
    /**
     * Extract mpesa reference code from model
     * @return mixed
     */
    public function getTransactionReference();

    /**
     * Extract mpesa transaction amount from model
     * @return mixed
     */
    public function getTransactionAmount();
}
