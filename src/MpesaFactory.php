<?php


namespace Snobole\Mpesa;

use Snobole\Mpesa\Transaction\Contracts\PayloadLogger;
use Snobole\Mpesa\Transaction\STKPushRepository;

class MpesaFactory
{
    /**
     * @param string $type 'c2b|b2c|stkpush'
     * @return PayloadLogger
     * @throws \ErrorException
     */
    public function getTransactor($type = 'stkpush')
    {
        switch (strtolower($type)) {
            case 'stkpush':
                return new STKPushRepository();
                break;
            default:
                throw new \ErrorException('Invalid Transaction Type');
        }
    }
}
