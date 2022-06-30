<?php

namespace Snobole\Mpesa\Providers;

use Snobole\Mpesa\Apis\STKPush;
use Botble\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Snobole\Mpesa\Models\MpesaSTKPush;
use Snobole\Mpesa\Services\Gateways\MpesaPaymentService;
use Exception;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerMpesaMethod'], 11, 2);
        add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithMpesa'], 11, 2);

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 93);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['MPESA'] = MPESA_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 20, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == MPESA_PAYMENT_METHOD_NAME) {
                $value = 'Mpesa';
            }

            return $value;
        }, 20, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == MPESA_PAYMENT_METHOD_NAME) {
                $value = Html::tag('span', PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label'])
                    ->toHtml();
            }

            return $value;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == MPESA_PAYMENT_METHOD_NAME) {
                $data = MpesaPaymentService::class;
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == MPESA_PAYMENT_METHOD_NAME) {
                $paymentService = new MpesaPaymentService;
                $paymentDetail = $paymentService->getPaymentDetails($payment->charge_id);

                if ($paymentDetail) {
                    $data = view('plugins/mpesa::detail', ['payment' => $paymentDetail, 'paymentModel' => $payment])->render();
                }
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_GET_REFUND_DETAIL, function ($data, $payment, $refundId) {
            if ($payment->payment_channel == MPESA_PAYMENT_METHOD_NAME) {
                $refundDetail = (new MpesaPaymentService)->getRefundDetails($refundId);
                if (!Arr::get($refundDetail, 'error')) {
                    $refunds = Arr::get($payment->metadata, 'refunds', []);
                    $refund = collect($refunds)->firstWhere('id', $refundId);
                    $refund = array_merge((array) $refund, Arr::get($refundDetail, 'data', []));
                    return array_merge($refundDetail, [
                        'view' => view('plugins/mpesa::refund-detail', ['refund' => $refund, 'paymentModel' => $payment])->render(),
                    ]);
                }
                return $refundDetail;
            }

            return $data;
        }, 20, 3);
    }

    /**
     * @param string $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings($settings)
    {
        return $settings . view('plugins/mpesa::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     */
    public function registerMpesaMethod($html, $data)
    {
        return $html . view('plugins/mpesa::methods', $data)->render();
    }

    /**
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function checkoutWithMpesa(array $data, Request $request)
    {
        if ($request->input('payment_method') == MPESA_PAYMENT_METHOD_NAME) {
            $supportedCurrencies = (new MpesaPaymentService())->supportedCurrencyCodes();

            if (!in_array($data['currency'], $supportedCurrencies)) {
                $data['error'] = true;
                $data['message'] = __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", ['name' => 'Mpesa', 'currency' => $data['currency'], 'currencies' => implode(', ', $supportedCurrencies)]);

                return $data;
            }

            $orderIds = (array) $request->input('order_id', []);
            $orderId = Arr::first($orderIds);
            $amount = $request->input('amount');
            $data['charge_id'] = $request->input('checkout_request_id');
            $status = PaymentStatusEnum::PENDING;

            try {
                if ($paymentLog = MpesaSTKPush::where('checkout_request_id', $data['charge_id'])->first()) {

                    if ($paymentLog->mpesa_receipt_number) {
                        $data['charge_id'] = $paymentLog->mpesa_receipt_number;
                        $status = PaymentStatusEnum::COMPLETED;
                    }
                }

                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'account_id'      => Arr::get($data, 'account_id'),
                    'amount'          => $amount,
                    'currency'        => $data['currency'],
                    'charge_id'       => $data['charge_id'],
                    'payment_channel' => MPESA_PAYMENT_METHOD_NAME,
                    'status'          => $status,
                    'order_id'        => $orderId,
                ]);

            } catch (Exception $exception) {
                $data['error'] = true;
                $data['message'] = json_encode($exception->getMessage());
            }
        }

        return $data;
    }
}
