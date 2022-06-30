@php $mpesaStatus = get_payment_setting('status', MPESA_PAYMENT_METHOD_NAME); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="data:image/png;base64,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"
                 alt="Mpesa">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://snobole.com/products/botbole-mpesa" target="_blank">Mpesa</a>
                    <p>{{ __('Customer can buy product and pay via Mpesa') }}</p>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-start" style="margin-top: 5px;">
                <div
                    class="payment-name-label-group @if (get_payment_setting('status', MPESA_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', MPESA_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-end">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($mpesaStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($mpesaStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', MPESA_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'Mpesa']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'Mpesa']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://developer.safaricom.co.ke" target="_blank">
                                        {{ __('Register an account at Safaricom Daraja') }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <span>{{ __('After registration at :name, you will have Client ID, Client Secret', ['name' => 'Mpesa']) }}</span>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('Enter Client ID, Secret into the box in right hand') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group mb-3">
                            <label class="text-title-field"
                                   for="mpesa_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_name"
                                   id="mpesa_name" data-counter="400"
                                   value="{{ get_payment_setting('name', MPESA_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'Mpesa'])) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_description">{{ trans('core/base::forms.description') }}</label>
                            <textarea class="next-input" name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_description" id="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_description">{{ get_payment_setting('description', MPESA_PAYMENT_METHOD_NAME, __('Payment with Mpesa')) }}</textarea>
                        </div>

                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank"
                                                                                                  href="https://developer.safaricom.co.ke">from Safaricom Daraja</a>:
                        </p>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_environment">{{ __('Environment') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_environment" id="mpesa_environment"
                                   value="{{ get_payment_setting('environment', MPESA_PAYMENT_METHOD_NAME, 'sandbox') }}" placeholder="sandbox">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_sender_phone">{{ __('Sender Phone') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_sender_phone" id="mpesa_sender_phone"
                                   value="{{ get_payment_setting('sender_phone', MPESA_PAYMENT_METHOD_NAME) }}" placeholder="e.g 2547000000">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_shortcode">{{ __('Shortcode') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_shortcode" id="mpesa_shortcode"
                                   value="{{ get_payment_setting('shortcode', MPESA_PAYMENT_METHOD_NAME) }}" placeholder="000000">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_confirmation_key">{{ __('Confirmation Key') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_confirmation_key" id="mpesa_confirmation_key"
                                   value="{{ get_payment_setting('confirmation_key', MPESA_PAYMENT_METHOD_NAME) }}" placeholder="Enter random key">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_passkey">{{ __('Passkey') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_passkey" id="mpesa_passkey"
                                   value="{{ get_payment_setting('passkey', MPESA_PAYMENT_METHOD_NAME) }}" placeholder="Enter passkey from Safaricom">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_key">{{ __('Key') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_key" id="mpesa_key"
                                   value="{{ get_payment_setting('key', MPESA_PAYMENT_METHOD_NAME) }}" placeholder="Enter client key from Safaricom">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-title-field" for="mpesa_secret">{{ __('Secret') }}</label>
                            <input type="password" class="next-input" id="mpesa_secret"
                                   name="payment_{{ MPESA_PAYMENT_METHOD_NAME }}_secret"
                                   value="{{ get_payment_setting('secret', MPESA_PAYMENT_METHOD_NAME) }}" placeholder="Enter client secret from Safaricom">
                        </div>

                        {!! apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, MPESA_PAYMENT_METHOD_NAME) !!}
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-end">
                <button class="btn btn-warning disable-payment-item @if ($mpesaStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($mpesaStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($mpesaStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
