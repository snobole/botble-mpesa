<?php

namespace Snobole\Mpesa\Transaction\Contracts;

use Illuminate\Http\Request;

interface PayloadLogger
{
    public function logPayload(Request $request);

}
