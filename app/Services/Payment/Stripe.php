<?php

namespace EHXDonate\Services\Payment;

class Stripe
{
    protected $settings;
    public function __construct()
    {
       
        $this->settings = $set['stripe'] ?? [];
    }
    public function register()
    {
        // Register scripts
        add_action('ehxdo_handle_payment_stripe', [$this, 'handlePayment'], 10, 2);
    }

    public function isEnabled()
    {
        return true;
    }

    public function handlePayment($donation, $data)
    {
        if (!$this->isEnabled()) {
            return;
        }
        $set = get_option('ehx_donate_settings_integration', []);
        $secret_key = $this->getSecretKey();
        $currency = $data['currency'] ?? 'GBP';
        $amount = $data['amount'] ?? 0;
        $body = [
            'payment_method_types[]' => 'card',
            'mode' => 'payment',
            'line_items[0][price_data][currency]' => $currency,
            'line_items[0][price_data][product_data][name]' => 'Donation',
            'line_items[0][price_data][unit_amount]' => $amount * 100,
            'line_items[0][quantity]' => 1,
            // ehx donate rest url 
            'success_url' => rest_url('ehx-donate/v1/payment/stripe/success/?session_id={CHECKOUT_SESSION_ID}&donation_id=' . $donation->id),
            'cancel_url' => rest_url('ehx-donate/v1/payment/stripe/cancel/?donation_id=' . $donation->id),
        ];
    
        $response = wp_remote_post("https://api.stripe.com/v1/checkout/sessions", [
            'method' => 'POST',
            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
            ],
            'body' => $body,
        ]);
    
        if (is_wp_error($response)) {
            return false;
        }
    
        $body = json_decode(wp_remote_retrieve_body($response), true);

        wp_send_json_success( [
            'redirect_url' => $body['url'],
        ] );
    }

    public function getSecretKey()
    {
        $set = get_option('ehx_donate_settings_integration', []);
        $settings = $set['stripe'] ?? [];
        return $settings['mode'] === 'live' ? $settings['live_clientSecret'] : $settings['clientSecret'];
    }

    public function getMode()
    {
        $set = get_option('ehx_donate_settings_integration', []);
        $settings = $set['stripe'] ?? [];
        return $settings['mode'] ?? 'test';
    }

    public function getCheckoutSession($session_id)
    {
        $secret_key = $this->getSecretKey();

        $response = wp_remote_get("https://api.stripe.com/v1/checkout/sessions/$session_id", [
            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}