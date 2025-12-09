<?php

namespace EHXDonate\Controllers;

use EHXDonate\Models\Donation;
use EHXDonate\Models\Transaction;
use EHXDonate\Models\Subscription;
use EHXDonate\Services\Payment\Stripe;

class PaymentController extends Controller
{
    // ========================SUCCESS URL (One-time + Subscription created)==============================
    public function stripeSuccess()
    {
        $session_id  = sanitize_text_field($_GET['session_id'] ?? '');
        $donation_id = intval($_GET['donation_id'] ?? 0);

        if (!$session_id || !$donation_id) {
            return wp_send_json_error(['message' => 'Invalid request'], 400);
        }

        $donation = Donation::find($donation_id);
        if (!$donation) {
            return wp_send_json_error(['message' => 'Donation not found'], 404);
        }

        $stripe  = new Stripe();
        $session = $stripe->getCheckoutSession($session_id);

        if (empty($session['mode'])) {
            return wp_send_json_error(['message' => 'Invalid Stripe session'], 400);
        }

        // ==============ONE-TIME PAYMENT===============
        if ($session['mode'] === 'payment') {
            $payment_intent_id = $session['payment_intent'] ?? null;
            $card = [];

            if ($payment_intent_id) {
                $payment_intent = $stripe->getPaymentIntent($payment_intent_id);
                $card = $payment_intent['charges']['data'][0]['payment_method_details']['card'] ?? [];
            }

            Transaction::create([
                'campaign_id'         => $donation->campaign_id,
                'donor_id'            => $donation->donor_id,
                'vendor_charge_id'    => $payment_intent_id,
                'user_id'             => $donation->user_id,
                'donation_id'         => $donation->id,
                'payment_method'      => 'stripe',
                'payment_method_type' => 'card',
                'card_last_4'         => $card['last4'] ?? '',
                'card_brand'          => $card['brand'] ?? '',
                // User paid = total_payment (includes processing_fee once)
                'payment_total'       => $donation->net_amount,
                'status'              => 'completed',
                'currency'            => $donation->currency,
                'payment_mode'        => $donation->payment_mode ?? 'one_time',
                'reporting_total'     => $donation->net_amount,
                'reporting_currency'  => $donation->currency,
                'reporting_exchange_rate' => 1,
            ]);

            $donation->payment_status = 'completed';
            $donation->payment_method = 'stripe';
            $donation->save();
        }

        // =================SUBSCRIPTION CREATION (first payment handled via webhook)============
        if ($session['mode'] === 'subscription') {
            // $donation->donation_type  = 'recurring';
            // $donation->payment_method = 'stripe';
            // $donation->save();

            $subscription = (new Subscription())->where('donation_id', $donation->id)->first();
            if (!$subscription) {
                error_log('No subscription found for donation: ' . $donation->id);
                return;
            }
            $subscription->vendor_subscription_id = $session['subscription'];
            $subscription->meta = json_encode($session);
            $subscription->save();
        }

        self::redirectToCampaignPage($donation_id);
    }

    // ====================CANCEL URL==================================
    public function stripeCancel()
    {
        error_log('stripeCancel');
        $donation_id = intval($_GET['donation_id'] ?? 0);
        if (!$donation_id) {
            return wp_send_json_error(['message' => 'Invalid request'], 400);
        }

        $donation = Donation::find($donation_id);
        if ($donation) {
            $donation->payment_status = 'failed';
            $donation->save();
        }

        self::redirectToCampaignPage($donation_id);
    }

    // ======================STRIPE WEBHOOK (RECURRING PAYMENTS)================================

    public function stripeWebhook()
    {
        $stripe = new Stripe();

        $payload    = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $event = $stripe->validateWebhook($payload, $sig_header);
        if (!$event) {
            return error_log('Invalid webhook');
        }
        $type = $event['type'] ?? '';
        $data = $event['data']['object'] ?? [];

        switch ($type) {
            case 'invoice.payment_succeeded':
                $this->handleRecurringPayment($data, $event);
                break;
            case 'invoice.payment_failed':
                $this->handleRecurringFailed($data);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionCancelled($data);
                break;
        }

        return wp_send_json_success(['message' => 'Webhook handled']);
    }

    // =========================RECURRING PAYMENT SUCCESS (first + renewals)=============================
    private function handleRecurringPayment($invoice, $event)
    {
        $vendor_subscription_id = $invoice['subscription'] ?? null;

        $donation_id = $invoice['subscription_details']['metadata']['donation_id']
        ?? $invoice['lines']['data'][0]['metadata']['donation_id']
        ?? null;

        error_log('Donation ID: ' . $donation_id);

        if (!$vendor_subscription_id) {
            return;
        }
        $subscription = (new Subscription())->where('donation_id', $donation_id)->first();
   
        if (!$subscription) {
            return;
        }

        // Related donation
        $donation = Donation::find($subscription->donation_id);
        if (!$donation) {
            return;
        }

        $currency  = strtoupper($invoice['currency'] ?? $donation->currency);
        $charge_id = $invoice['charge'] ?? null;

        // How many transactions already exist for this subscription?
        $existingCount =(new Transaction())->where('subscription_id', $subscription->id)->count();

        // FIRST SUBSCRIPTION PAYMENT:
        //  - we want to record total_payment (donation + processing_fee)
        // RENEWALS:
        //  - we record only base donation amount ($subscription->amount)
        if ($existingCount === 0) {
            // First invoice -> includes processing fee in total_payment
            $payment_total = $donation->net_amount;       // donation + processing_fee (and tip if you use it)
            $reporting_total = $invoice['amount_paid'] / 100; // what Stripe actually charged
        } else {
            // Renewal -> only donation amount (no internal processing fee)
            $payment_total   = $subscription->amount;
            $reporting_total = $invoice['amount_paid'] / 100; // should match subscription->amount from Stripe
        }

        Transaction::create([
            'campaign_id'         => $donation->campaign_id,
            'donor_id'            => $donation->donor_id,
            'subscription_id'     => $subscription->id,
            'donation_id'         => $donation->id,
            'user_id'             => $donation->user_id,
            'vendor_charge_id'    => $charge_id,
            'payment_method'      => 'stripe',
            'payment_method_type' => 'card',
            'card_last_4'         => '',
            'card_brand'          => '',
            'payment_total'       => $payment_total,
            'status'              => 'completed',
            'currency'            => $currency,
            'payment_mode'        => 'recurring',
            'reporting_total'     => $reporting_total,
            'reporting_currency'  => $currency,
            'reporting_exchange_rate' => 1,
        ]);

        $donation->payment_status = 'completed';
        $donation->save();
        $nextBillingDate = $this->getNextBillingDateFromInvoice($invoice);
        error_log('Next payment attempt: ' . $nextBillingDate);
        $subscription->next_payment_date = $nextBillingDate;
        $subscription->save();
    }

    public function getNextBillingDateFromInvoice($invoice, $format = 'Y-m-d H:i:s')
    {
        // 1. Must be a subscription invoice
        if (empty($invoice['subscription'])) {
            return null;
        }

        // 2. Ensure line items exist
        if (
            empty($invoice['lines']) ||
            empty($invoice['lines']['data']) ||
            !is_array($invoice['lines']['data'])
        ) {
            return null;
        }

        // 3. Search for the subscription line item
        foreach ($invoice['lines']['data'] as $line) {
            if (
                isset($line['type']) &&
                $line['type'] === 'subscription' &&
                !empty($line['period']['end'])
            ) {
                $timestamp = (int) $line['period']['end'];

                // Return formatted date instead of number
                return date($format, $timestamp);
            }
        }

        return null;
    }


    // ======================================================
    // RECURRING PAYMENT FAILED
    // ======================================================
    private function handleRecurringFailed($invoice)
    {
        $vendor_subscription_id = $invoice['subscription'] ?? null;
        if (!$vendor_subscription_id) {
            return;
        }

        $subscription = (new Subscription())->where('vendor_subscription_id', $vendor_subscription_id)->first();
        if (!$subscription) {
            return;
        }

        $donation = Donation::find($subscription->donation_id);
        if (!$donation) {
            return;
        }

        $donation->payment_status = 'failed';
        $donation->save();

        $subscription->status = 'past_due';
        $subscription->save();
    }

    // ======================================================
    // SUBSCRIPTION CANCELLED
    // ======================================================
    private function handleSubscriptionCancelled($subscriptionObj)
    {
        $vendor_subscription_id = $subscriptionObj['id'] ?? null;
        if (!$vendor_subscription_id) {
            return;
        }

        $subscription = (new Subscription())->where('vendor_subscription_id', $vendor_subscription_id)->first();
        if (!$subscription) {
            return;
        }

        $donation = Donation::find($subscription->donation_id);
        if ($donation) {
            $donation->payment_status = 'failed'; // or 'cancelled' if you add that enum
            $donation->save();
        }

        $subscription->status = 'cancelled';
        $subscription->save();
    }

    // ======================================================
    public static function redirectToCampaignPage($donation_id)
    {
        wp_redirect(site_url('/ehxdo-success/' . $donation_id));
        exit();
    }
}
