<?php


namespace Snobole\Mpesa\Transaction;

use Snobole\Mpesa\Models\MpesaSTKPush;
use Snobole\Mpesa\Transaction\Contracts\PayloadLogger;
use Illuminate\Http\Request;

class STKPushRepository implements PayloadLogger
{

    /**
     * @param Request $request
     * @return MpesaSTKPush
     * @throws \ErrorException
     */
    public function logPayload(Request $request)
    {

        $payload = json_decode($request->getContent());

//        Storage::put('stk-push-attempt-'.time().'.txt', $request->getContent());

        if (property_exists($payload, 'Body') && $payload->Body->stkCallback->ResultCode == '0') {

            $merchant_request_id = $payload->Body->stkCallback->MerchantRequestID;
            $checkout_request_id = $payload->Body->stkCallback->CheckoutRequestID;

            $stk_push_model = MpesaSTKPush::where('merchant_request_id', $merchant_request_id)
                ->where('checkout_request_id', $checkout_request_id)->first();

            $data = [
                'result_desc' => $payload->Body->stkCallback->ResultDesc,
                'result_code' => $payload->Body->stkCallback->ResultCode,
                'merchant_request_id' => $merchant_request_id,
                'checkout_request_id' => $checkout_request_id,
                'amount' => $payload->Body->stkCallback->CallbackMetadata->Item[0]->Value,
                'mpesa_receipt_number' => $payload->Body->stkCallback->CallbackMetadata->Item[1]->Value,
                'transaction_date' => $payload->Body->stkCallback->CallbackMetadata->Item[2]->Value,
                'phone_number' => $payload->Body->stkCallback->CallbackMetadata->Item[3]->Value,
            ];

            if ($stk_push_model) {

                $stk_push_model->fill($data)->save();

            } else {
                $stk_push_model = MpesaSTKPush::create($data);
            }

            return $stk_push_model;
        }
        throw new \ErrorException("Deformed Payload");
    }

}
