<?php

namespace Snobole\Mpesa\Http\Controllers;

use App\Http\Controllers\Controller;
use OrderHelper;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Snobole\Mpesa\Http\Requests\MpesaSTKPushSimulateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Snobole\Mpesa\Apis\STKPush;
use Snobole\Mpesa\Models\MpesaSTKPush;
use function config;
use function response;
use function route;

class STKPushController extends Controller
{
    private $result_desc = 'An error occurred';
    private $result_code = 1;
    private $http_code = 400;
    protected $orderRepository;

    public function simulate(MpesaSTKPushSimulateRequest $request)
    {
        $shortcode = get_payment_setting('shortcode', MPESA_PAYMENT_METHOD_NAME);
        $passkey = get_payment_setting('passkey', MPESA_PAYMENT_METHOD_NAME);
        $confirmation_key = get_payment_setting('confirmation_key', MPESA_PAYMENT_METHOD_NAME);
        $sender_phone = get_payment_setting('sender_phone', MPESA_PAYMENT_METHOD_NAME);

        $consumerKey = get_payment_setting('key', MPESA_PAYMENT_METHOD_NAME);
        $consumerSecret = get_payment_setting('secret', MPESA_PAYMENT_METHOD_NAME);
        $env = get_payment_setting('environment', MPESA_PAYMENT_METHOD_NAME);

        $config = [
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
            'env' => $env,
        ];

        if ($shortcode && $passkey && $confirmation_key && $sender_phone && $consumerKey && $consumerSecret && $env) {

            $stk_push_simulator = (new STKPush())
                ->setShortCode($shortcode)
                ->setPassKey($passkey)
                ->setAmount($request->amount)
                ->setSenderPhone($sender_phone)
                ->setPayerPhone($request->phone)
                ->setAccountReference(($request->account_reference ?? 'Default'))
                ->setReceivingShortcode($shortcode)
                ->setCallbackUrl(route('api.mpesa.stk-push.confirm', $confirmation_key))
                ->setRemarks('Pay your bill')
                ->simulate($config);

            if (! $stk_push_simulator->failed()) {

                $this->http_code = 200;

            }

            $this->result_desc = $stk_push_simulator->getResponse();

        } else {
            $this->result_desc = 'STK Push request failed: Missing important parameters';
        }

        return response()->json([
            'message' => $this->result_desc
        ], $this->http_code);

    }

    public function confirm(Request $request)
    {
        $confirmation_key = get_payment_setting('confirmation_key', MPESA_PAYMENT_METHOD_NAME);

        if ($request->confirmation_key == $confirmation_key) {

            $stk_push_confirm = (new STKPush())->confirm($request);

            if ($stk_push_confirm->failed()) {

                Log::error($stk_push_confirm->getResponse());

            } else {

                if ($order = app(OrderInterface::class)->getFirstBy(['token' => $stk_push_confirm->getAccountReference()])) {
                    OrderHelper::confirmPayment($order);
                }
            }

        } else {
            $this->result_desc = 'STK Push confirmation failed: Confirmation key mismatch';
        }

        return response()->json([
            'ResultCode' => $this->result_code,
            'ResultDesc' => $this->result_desc,
        ]);
    }

    public function show(Request $request, $token) {
        if ($stk_push = MpesaSTKPush::where(['account_reference' => $token,])->first()) {
            return response()->json([
                'data' => [
                    'merchant_request_id' => $stk_push->merchant_request_id,
                    'checkout_request_id' => $stk_push->checkout_request_id,
                ]
            ]);
        }

        return response()->json([
            'data' => []
        ]);
    }
}
