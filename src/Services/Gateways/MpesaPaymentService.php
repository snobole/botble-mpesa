<?php

namespace Snobole\Mpesa\Services\Gateways;

use Snobole\Mpesa\Services\Abstracts\MpesaPaymentAbstract;
use Exception;
use Illuminate\Http\Request;

class MpesaPaymentService extends MpesaPaymentAbstract
{
    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws Exception
     */
    public function makePayment(Request $request)
    {
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function afterMakePayment(Request $request)
    {
    }
}
