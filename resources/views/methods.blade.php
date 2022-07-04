@if (get_payment_setting('status', MPESA_PAYMENT_METHOD_NAME) == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_{{ MPESA_PAYMENT_METHOD_NAME }}"
               value="{{ MPESA_PAYMENT_METHOD_NAME }}" data-bs-toggle="collapse" data-bs-target=".payment_{{ MPESA_PAYMENT_METHOD_NAME }}_wrap"
               data-parent=".list_payment_method"
               @if (setting('default_payment_method') == MPESA_PAYMENT_METHOD_NAME) checked @endif
        >
        <label for="payment_{{ MPESA_PAYMENT_METHOD_NAME }}">{{ get_payment_setting('name', MPESA_PAYMENT_METHOD_NAME) }}</label>
        <div class="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == MPESA_PAYMENT_METHOD_NAME) show @endif">
            <div class="pt-3">
                <p>{!! get_payment_setting('description', MPESA_PAYMENT_METHOD_NAME, __('Payment with Mpesa')) !!}</p>
            </div>
            @php $supportedCurrencies = (new \Snobole\Mpesa\Services\Gateways\MpesaPaymentService)->supportedCurrencyCodes(); @endphp
            @if (!in_array(get_application_currency()->title, $supportedCurrencies))
                <div class="alert alert-warning" style="margin-top: 15px;">
                    {{ __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", ['name' => 'Mpesa', 'currency' => get_application_currency()->title, 'currencies' => implode(', ', $supportedCurrencies)]) }}
                    @php
                        $currencies = get_all_currencies();

                        $currencies = $currencies->filter(function ($item) use ($supportedCurrencies) { return in_array($item->title, $supportedCurrencies); });
                    @endphp
                    @if (count($currencies))
                        <div style="margin-top: 10px;">{{ __('Please switch currency to any supported currency') }}:&nbsp;&nbsp;
                            @foreach ($currencies as $currency)
                                <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                                @if (!$loop->last)
                                    &nbsp; | &nbsp;
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
            <div class="row mt-3">
                <div id="stk-push-payment-form" class="col-sm-6 col-12">
                    <div class="form-group mb-3">
                        <input id="stk_push_phone" type="text" class="form-control address-control-item address-control-item-required checkout-input" placeholder="+2547000000" name="phone">
                    </div>
                    <div class="form-group mb-3">
                        <button type="button" class="btn stk-push-btn btn-info text-white" data-processing-text="Processing. Please wait..." data-error-header="Error" style="padding: 10px 20px;">
                            Pay
                        </button>
                    </div>
                </div>
                <div id="stk-push-payment-status-box" style="display:none" class="col-sm-6 col-12 mb-3">
                    <div class="p-3 bg-info text-white rounded">
                        Your payment request has been received and is being processed. Your order will be updated as soon as the payment is received.
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="merchant_request_id" name="merchant_request_id">
        <input type="hidden" id="checkout_request_id" name="checkout_request_id">
    </li>

    <script>
        $(document).ready(function () {

            $(document).off('click', '.stk-push-btn').on('click', '.stk-push-btn', function (event) {
                event.preventDefault();

                let _self = $(this);
                _self.attr('disabled', 'disabled');
                let submitInitialText = _self.html();
                _self.html('<i class="fa fa-gear fa-spin"></i> ' + _self.data('processing-text'));

                $.ajax({
                    method: "POST",
                    url: "/api/payment/stk-push/simulate",
                    data: {
                        phone: $('#stk_push_phone').val(),
                        amount: {{$amount}},
                        account_reference: '{{request()->token}}'
                    },
                    success: function(result,status,xhr) {
                        $('#merchant_request_id').val(result.message.MerchantRequestID)
                        $('#checkout_request_id').val(result.message.CheckoutRequestID)
                        checkPaymentStatus()
                        if (typeof Botble != 'undefined') {
                            Botble.showSuccess(result, 'Success');
                        }

                    },
                    error: function(xhr,status,error) {
                        if (typeof Botble != 'undefined') {
                            Botble.showError(error, _self.data('error-header'));
                        }
                        checkPaymentStatus()
                    },
                }).done(function( msg ) {
                    _self.removeAttr('disabled');
                    _self.html(submitInitialText);
                });
            });

            $(document).off('click', '.payment-checkout-btn').on('click', '.payment-checkout-btn', function (event) {
                event.preventDefault();
                let _self = $(this);
                let form = _self.closest('form');
                form.submit();
            });

            //Check for transaction status
            (function poll() {
                setTimeout(function() {
                    $.ajax({
                        url: `/api/payment/stk-push/{{request()->token}}/show`,
                        type: "GET",
                        success: function(response) {
                            if(response.data) {
                                $('#merchant_request_id').val(response.data.merchant_request_id)
                                $('#checkout_request_id').val(response.data.checkout_request_id)

                                checkPaymentStatus()
                            }

                        },
                        error: function(xhr,status,error) {
                            checkPaymentStatus()
                        },
                        dataType: "json",
                        complete: poll,
                        timeout: 5000
                    })
                }, 10000);
            })();

            function checkPaymentStatus() {
                if($('#merchant_request_id').val() && $('#checkout_request_id').val()) {
                    $('#stk-push-payment-form').hide()
                    $('#stk-push-payment-status-box').show()
                } else {
                    $('#stk-push-payment-form').show()
                    $('#stk-push-payment-status-box').hide()
                }
            }
        });
    </script>
@endif
