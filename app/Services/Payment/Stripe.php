<?php

namespace EHXDonate\Services\Payment;

class Stripe
{
    protected $settings;

    public function __construct()
    {
        $set = get_option('ehx_donate_settings_integration', []);
        $this->settings = $set['stripe'] ?? [];
    }

    public function register()
    {
        add_action('ehxdo_handle_payment_stripe', [$this, 'handlePayment'], 10, 2);
    }

    public function isEnabled()
    {
        return true;
    }

    /**
     * Create Stripe Checkout session for one-time or recurring donation
     */
    public function handlePayment($donation, $data)
    {
        if (!$this->isEnabled()) {
            return;
        }
     

        $secret_key  = $this->getSecretKey();
        $currency    = $data['currency'] ?? 'GBP';
        $amount      = (float) ($donation->net_amount ?? 0); // This should be what you actually charge in Stripe
        $donationType = $data['donation_type'] ?? 'one-time';
        $isRecurring = ($donationType === 'recurring');
        $interval    = $data['interval'] ?? 'month'; // day, week, month, year, quarter (your custom)
        
        $success_url = rest_url(
            'ehx-donate/v1/payment/stripe/success/?session_id={CHECKOUT_SESSION_ID}&donation_id=' . $donation->id
        );
        $cancel_url  = rest_url(
            'ehx-donate/v1/payment/stripe/cancel/?donation_id=' . $donation->id
        );

        // ------------ONE-TIME PAYMENT-------------
        if (!$isRecurring) {
            $amount = (float) ($donation->net_amount ?? 0);
            $body = [
                'payment_method_types[]'                     => 'card',
                'mode'                                      => 'payment',
                'line_items[0][price_data][currency]'       => $currency,
                'line_items[0][price_data][product_data][name]' => 'Donation',
                'line_items[0][price_data][unit_amount]'    => (int) round($amount * 100),
                'line_items[0][quantity]'                   => 1,
                'success_url'                               => $success_url,
                'cancel_url'                                => $cancel_url,
                // optional metadata if you ever need
                'metadata[donation_id]'                     => $donation->id,
            ];
        }

        // -----------RECURRING PAYMENT (SUBSCRIPTION)--------------
        else {
            $interval_count = 1;
            if ($interval === 'quarter') {
                // convert quarter to 3-month interval for Stripe
                $interval = 'month';
                $interval_count = 3;
            }

            $body = [
                'payment_method_types[]'                         => 'card',
                'mode'                                          => 'subscription',
                'line_items[0][price_data][currency]'           => $currency,
                'line_items[0][price_data][recurring][interval]' => $interval,
                'line_items[0][price_data][recurring][interval_count]' => $interval_count,
                'line_items[0][price_data][product_data][name]' => 'Recurring Donation',
                'line_items[0][price_data][unit_amount]'        => (int) round($amount * 100),
                'line_items[0][quantity]'                       => 1,
                'success_url'                                   => $success_url,
                'cancel_url'                                    => $cancel_url,
                'metadata[donation_id]'                         => $donation->id, // for webhook if needed
                'subscription_data[metadata][donation_id]' => $donation->id,
            ];
        }

        $response = wp_remote_post("https://api.stripe.com/v1/checkout/sessions", [
            'method'  => 'POST',
            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
            ],
            'body'    => $body,
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (empty($body['url'])) {
            wp_send_json_error(['message' => 'Stripe session creation failed.']);
        }

        wp_send_json_success([
            'redirect_url' => $body['url'],
        ]);
    }

    public function getSecretKey()
    {
        return ($this->settings['mode'] ?? 'test') === 'live'
            ? ($this->settings['live_clientSecret'] ?? '')
            : ($this->settings['clientSecret'] ?? '');
    }

    public function getWebhookSecret()
    {
        // if mode is test then return test_webhook_secret else return live_webhook_secret
        return ($this->settings['mode'] ?? 'test') === 'live'
            ? ($this->settings['live_webhook_secret'] ?? '')
            : ($this->settings['test_webhook_secret'] ?? '');
    }

    public function getCheckoutSession($session_id)
    {
        return $this->getStripeObject("checkout/sessions/$session_id");
    }

    public function getPaymentIntent($payment_intent_id)
    {
        return $this->getStripeObject("payment_intents/$payment_intent_id");
    }

    public function getSubscription($sub_id)
    {
        return $this->getStripeObject("subscriptions/$sub_id");
    }

    public function getInvoice($invoice_id)
    {
        return $this->getStripeObject("invoices/$invoice_id");
    }

    public function getCharge($charge_id)
    {
        return $this->getStripeObject("charges/$charge_id");
    }

    private function getStripeObject($path)
    {
        $secret_key = $this->getSecretKey();

        $response = wp_remote_get("https://api.stripe.com/v1/$path", [
            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
            ],
        ]);

        if (is_wp_error($response)) {
            return [];
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function validateWebhook($payload, $sig_header)
    {
        $secret = $this->getWebhookSecret();
        var_dump($secret);
        // if (!$secret) {
        //     return false;
        // }

        // NOTE: this is a simplified HMAC check, not Stripe's official scheme,
        // but matches the logic you've chosen
        // $expected = hash_hmac('sha256', $payload, $secret);

        // if (!hash_equals($expected, $sig_header)) {
        //     return false;
        // }

        return json_decode($payload, true);
    }

    public function getMode()
    {
        return $this->settings['mode'] ?? 'test';
    }
}
